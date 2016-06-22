<?php
/**
 * Internal library of functions for module unicko
 *
 * All the unicko specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->libdir.'/filelib.php');

require_once($CFG->dirroot . '/user/selector/lib.php');

/**
 * Get private room
 *
 * @param integer $roomid
 * @param integer $userid
 * @return object
 */
function unicko_get_private_room($roomid, $userid) {
    global $DB;

    $sql = "SELECT u.*, ru.affiliation, owner.firstname as ownerfirstname, owner.lastname as ownerlastname
            FROM {unicko} u
            LEFT JOIN {unicko_room_users} ru ON (ru.roomid = u.id AND ru.userid = :userid)
            JOIN {unicko_room_users} ruo ON (ruo.roomid = u.id AND ruo.affiliation = 0)
            JOIN {user} owner ON (owner.id = ruo.userid)
            WHERE
                u.id = :roomid";
    $params = array('roomid' => $roomid, 'userid' => $userid);
    $unicko = $DB->get_record_sql($sql, $params, MUST_EXIST);
    return $unicko;
}

/**
 * Get private room and make sure the user is the room owner
 *
 * @param integer $roomid
 * @param integer $userid
 * @return object
 */
function unicko_get_private_room_as_owner($roomid, $userid) {
    global $DB;

    $sql = "SELECT u.*
        FROM {unicko} u
        JOIN {unicko_room_users} ru ON (ru.roomid = u.id)
        WHERE
            u.id = :roomid AND
            ru.userid = :userid AND
            ru.affiliation = 0";
    $params = array('roomid' => $roomid, 'userid' => $userid);
    $room = $DB->get_record_sql($sql, $params, MUST_EXIST);
    return $room;
}

/**
 * Get private rooms of a user
 *
 * @param integer $userid
 * @return object
 */
function unicko_get_private_rooms($userid) {
    global $DB;

    $sql = "SELECT
              u.id as roomid,
              u.name as roomname,
              c.id as courseid,
              c.shortname as courseshortname,
              ru.affiliation,
              owner.firstname as ownerfirstname,
              owner.lastname as ownerlastname,
              COUNT(ruc.userid) as count
            FROM {unicko} u
            JOIN {course} c ON (c.id = u.course)
            JOIN {unicko_room_users} ru ON (ru.roomid = u.id AND ru.userid = :userid)
            JOIN {unicko_room_users} ruo ON (ruo.roomid = u.id AND ruo.affiliation = 0)
            JOIN {user} owner ON (owner.id = ruo.userid)
            JOIN {unicko_room_users} ruc ON (ruc.roomid = u.id)
            WHERE
                u.type = 1
            GROUP BY u.id, u.name, c.id, c.shortname, ru.affiliation, owner.firstname, owner.lastname
            ORDER BY u.id";
    $params = array('userid' => $userid);
    $rooms = $DB->get_records_sql($sql, $params);
    return $rooms;
}

/**
 * Sends a message to a user to notify that he was enroled to a private room.
 *
 * @param object $a lots of useful information that can be used in the message
 *      subject and body.
 *
 * @return int|false as for {@link message_send()}.
 */
function unicko_send_enrolment($submitter, $recipient, $a) {
    $a->username     = fullname($recipient);

    // Prepare the message.
    $eventdata = new stdClass();
    $eventdata->component         = 'mod_unicko';
    $eventdata->name              = 'enrolment';
    $eventdata->notification      = 1;

    $eventdata->userfrom          = $submitter;
    $eventdata->userto            = $recipient;
    $eventdata->subject           = get_string('emailenrolmentsubject', 'unicko', $a);
    $eventdata->fullmessage       = get_string('emailenrolmentbody', 'unicko', $a);
    $eventdata->fullmessageformat = FORMAT_PLAIN;
    $eventdata->fullmessagehtml   = '';

    $eventdata->smallmessage      = get_string('emailenrolmentsmall', 'unicko', $a);
    $eventdata->contexturl        = $a->roomurl;
    $eventdata->contexturlname    = $a->roomname;

    return message_send($eventdata);
}

/**
 * Handle the user_deleted event.
 *
 * Delete private rooms owned by the user and unenrol from others
 *
 * @param object $event the event object.
 */
function unicko_user_deleted($event) {
    global $DB;

    $userid = $event->id;
    $sql = "SELECT u.*
        FROM {unicko} u
        JOIN {unicko_room_users} ru ON (ru.roomid = u.id)
        WHERE
            u.type = 1 AND
            ru.userid = :userid AND
            ru.affiliation = 0";
    $params = array('userid' => $userid);
    $rooms = $DB->get_records_sql($sql, $params);
    foreach($rooms as $room) {
        $DB->delete_records('unicko_room_users', array('roomid'=>$room->id));
        $DB->delete_records('unicko', array('id'=>$room->id));
    }
    $DB->delete_records('unicko_room_users', array('userid'=>$userid));
    return true;
}

/**
 * Course students
 */
class unicko_course_user_selector extends user_selector_base {
    protected $courseid;

    public function __construct($name, $options) {
        $this->courseid  = $options['courseid'];
        parent::__construct($name, $options);
    }

    /**
     * Candidate users
     * @param string $search
     * @return array
     */
    public function find_users($search) {
        global $DB;

        // By default wherecondition retrieves all users except the deleted, not confirmed and guest.
        list($wherecondition, $params) = $this->search_sql($search, 'u');
        $params['courseid'] = $this->courseid;
        // function missing from moodle 2.3
        //list($sort, $sortparams) = users_order_by_sql('u', $search, $this->accesscontext);
        $sort = 'u.lastname, u.firstname';
        $sortparams = array();

        $fields      = 'SELECT ' . $this->required_fields_sql('u');
        $countfields = 'SELECT COUNT(1)';
        $order = ' ORDER BY ' . $sort;

        $sql = " FROM {course} c
            JOIN {context} ct ON (ct.instanceid = c.id)
            JOIN {role_assignments} ra ON (ra.contextid = ct.id)
            JOIN {user} u ON u.id = ra.userid
            JOIN {role} r ON (r.id = ra.roleid)
            WHERE
                $wherecondition AND
                c.id = :courseid";

        if (!$this->is_validating()) {
            $potentialmemberscount = $DB->count_records_sql($countfields . $sql, $params);
            if ($potentialmemberscount > 100) {
                return $this->too_many_results($search, $potentialmemberscount);
            }
        }

        $availableusers = $DB->get_records_sql($fields . $sql . $order, array_merge($params, $sortparams));

        if (empty($availableusers)) {
            return array();
        }

        if ($search) {
            $groupname = get_string('studentsmatching', 'unicko', $search);
        } else {
            $groupname = get_string('students', 'unicko');
        }

        return array($groupname => $availableusers);
    }

    protected function get_options() {
        $options = parent::get_options();
        $options['courseid'] = $this->courseid;
        $options['file']    = 'mod/unicko/locallib.php';
        return $options;
    }
}

/**
 * Room members
 */
class unicko_room_members extends user_selector_base {
    protected $roomid;

    public function __construct($name, $options) {
        $this->roomid  = $options['roomid'];
        parent::__construct($name, $options);
    }

    /**
     * Candidate users
     * @param string $search
     * @return array
     */
    public function find_users($search) {
        global $DB;

        // By default wherecondition retrieves all users except the deleted, not confirmed and guest.
        list($wherecondition, $params) = $this->search_sql($search, 'u');
        $params['roomid'] = $this->roomid;
        // function missing from moodle 2.3
        //list($sort, $sortparams) = users_order_by_sql('u', $search, $this->accesscontext);
        $sort = 'u.lastname, u.firstname';
        $sortparams = array();

        $fields      = 'SELECT ' . $this->required_fields_sql('u');
        $countfields = 'SELECT COUNT(1)';
        $order = ' ORDER BY ' . $sort;

        $sql = " FROM {unicko_room_users} ru
            JOIN {user} u ON (u.id = ru.userid)
            WHERE
                $wherecondition AND
                ru.roomid = :roomid";

        if (!$this->is_validating()) {
            $potentialmemberscount = $DB->count_records_sql($countfields . $sql, $params);
            if ($potentialmemberscount > 100) {
                return $this->too_many_results($search, $potentialmemberscount);
            }
        }

        $availableusers = $DB->get_records_sql($fields . $sql . $order, array_merge($params, $sortparams));

        if (empty($availableusers)) {
            return array();
        }

        if ($search) {
            $groupname = get_string('membersmatching', 'unicko', $search);
        } else {
            $groupname = get_string('members', 'unicko');
        }

        return array($groupname => $availableusers);
    }

    protected function get_options() {
        $options = parent::get_options();
        $options['roomid'] = $this->roomid;
        $options['file']    = 'mod/unicko/locallib.php';
        return $options;
    }
}

/**
 * Generate a room join form
 *
 * @param object $data
 * @return string
 */
function unicko_get_join_form($data) {
    global $USER, $DB;

    $cfg = get_config('unicko');
    $url = $cfg->server_url . '/api';

    $payload = array(
        "request_type" => "room_login",
        "user_ext_id" => (string) $USER->id,
        "user_given_name" => $USER->firstname,
        "user_family_name" => $USER->lastname,
        "course_ext_id" => $data['course_id'],
        "course_name" => $data['course_name'],
        "course_role" => $data['course_role'],
        "room_ext_id" => $data['room_id'],
        "room_name" => $data['room_name'],
        "room_lang" => $data['room_lang'],
        "room_transient" => !empty( $data['room_transient'] ) && $data['room_transient'] === true,
        "room_affiliation" => $data['room_affiliation']
    );

    $version = 3;
    $expire = 60; // 1 mintue
    $consumer_key = $cfg->host;
    $consumer_secret = $cfg->secret;
    $request = unicko_encode_request($payload, $version, $expire, $consumer_key, $consumer_secret);

    $form = '<form id="unicko-form" action="'.$url.'" method="POST" enctype="application/x-www-form-urlencoded">';
    $form .= '<input type="hidden" name="signed_request" value=\''.$request.'\' />';
    $form .= '</form>';
    return $form;
}

/**
 * Generate a room manage form
 *
 * @param object $data
 * @return string
 */
function unicko_get_manage_form($data) {
    global $USER, $DB;

    $cfg = get_config('unicko');
    $url = $cfg->server_url . '/api';

    $payload = array(
        "request_type" => "room_manage",
        "user_ext_id" => (string) $USER->id,
        "user_given_name" => $USER->firstname,
        "user_family_name" => $USER->lastname,
        "course_ext_id" => $data['course_id'],
        "course_name" => $data['course_name'],
        "course_role" => $data['course_role'],
        "room_ext_id" => $data['room_id'],
        "room_name" => $data['room_name'],
        "room_lang" => $data['room_lang'],
        "room_transient" => false,
        "room_affiliation" => $data['room_affiliation']
    );

    $version = 3;
    $expire = 60; // 1 mintue
    $consumer_key = $cfg->host;
    $consumer_secret = $cfg->secret;
    $request = unicko_encode_request($payload, $version, $expire, $consumer_key, $consumer_secret);

    $form = '<form id="unicko-form" action="'.$url.'" method="POST" enctype="application/x-www-form-urlencoded">';
    $form .= '<input type="hidden" name="signed_request" value=\''.$request.'\' />';
    $form .= '</form>';
    return $form;
}

/**
 * Generate a room manage form
 *
 * @param object $data
 * @return string
 */
function unicko_get_recordings_form($data) {
    global $USER, $DB;

    $cfg = get_config('unicko');
    $url = $cfg->server_url . '/api';

    $payload = array(
        "request_type" => "room_recordings",
        "user_ext_id" => (string) $USER->id,
        "user_given_name" => $USER->firstname,
        "user_family_name" => $USER->lastname,
        "course_ext_id" => $data['course_id'],
        "course_name" => $data['course_name'],
        "course_role" => $data['course_role'],
        "room_ext_id" => $data['room_id'],
        "room_name" => $data['room_name'],
        "room_lang" => $data['room_lang'],
        "room_transient" => false,
        "room_affiliation" => $data['room_affiliation']
    );

    $version = 3;
    $expire = 60; // 1 mintue
    $consumer_key = $cfg->host;
    $consumer_secret = $cfg->secret;
    $request = unicko_encode_request($payload, $version, $expire, $consumer_key, $consumer_secret);

    $form = '<form id="unicko-form" action="'.$url.'" method="POST" enctype="application/x-www-form-urlencoded">';
    $form .= '<input type="hidden" name="signed_request" value=\''.$request.'\' />';
    $form .= '</form>';
    return $form;
}

/**
 * Mark the activity completed (if required) and trigger the course_module_viewed event.
 *
 * @param  stdClass $unicko  unicko object
 * @param  stdClass $course  course object
 * @param  stdClass $cm      course module object
 * @param  stdClass $context context object
 * @since Moodle 2.9
 */
function unicko_view($unicko, $course, $cm, $context) {
    // Completion.
    $completion = new completion_info($course);
    $completion->set_module_viewed($cm);
    // Trigger course_module_viewed event.
    $params = array(
        'context' => $context,
        'objectid' => $unicko->id
    );
    $event = \mod_unicko\event\course_module_viewed::create($params);
    $event->add_record_snapshot('course_modules', $cm);
    $event->add_record_snapshot('course', $course);
    $event->add_record_snapshot('unicko', $unicko);
    $event->trigger();
}

/**
 * Encode a request
 *
 * @param object $paylaod
 * @param int $version
 * @param int $expire
 * @param string $consumer_key
 * @param string $consumer_secret
 * @return string
 */
function unicko_encode_request($payload, $version, $expire, $consumer_key, $consumer_secret) {
    $algorithm = "HMAC-SHA256";
    $nonce = generate_nonce();
    $issued_at = time();
    $expires = $issued_at + $expire;
    $payload["version"] = $version;
    $payload["consumer_key"] = $consumer_key;
    $payload["algorithm"] = $algorithm;
    $payload["nonce"] = $nonce;
    $payload["issued_at"] = $issued_at;
    $payload["expires"] = $expires;
    return unicko_sign_request($payload, $consumer_secret);
}

/**
 * Sign a string with a given secret
 *
 * @param object $payload
 * @param string $secret
 * @return string
 */
function unicko_sign_request($payload, $secret) {
    $data = base64_url_encode(json_encode($payload));
    $signature = base64_url_encode(hash_hmac('sha256', $data, $secret, $raw = true));
    return $signature . "." . $data;
}

/**
 * Encode a string with URL-safe Base64.
 *
 * @param string $input
 * @return string
 */
function base64_url_encode($input) {
  return strtr(rtrim(base64_encode($input), '='), '+/', '-_');
}

/**
 * Generate random nonce
 *
 * @return string
 */
function generate_nonce() {
    return base64_encode(md5(rand(0, time()), true));
}
