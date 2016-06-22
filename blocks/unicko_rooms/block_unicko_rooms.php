<?php
/**
 * Unicko block caps.
 *
 * @package    block_unicko_rooms
 * @copyright  2015 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_unicko_rooms extends block_list {

    function init() {
        $this->title = get_string('pluginname', 'block_unicko_rooms');
    }

    function get_content() {
        global $CFG, $DB, $USER, $OUTPUT, $COURSE;

        if ($this->content !== null) {
          return $this->content;
        }

        $this->content         = new stdClass;
        $this->content->items  = array();
        $this->content->icons  = array();
        $this->content->footer = '';

        if (empty($this->instance) or !isloggedin() or isguestuser()) {
            return $this->content;
        }

        $context = context_course::instance($COURSE->id);
        $manager = has_capability('mod/unicko:accessallprivaterooms', $context);

        $params = array();
        $sql = "SELECT u.id, u.name, owner.firstname, owner.lastname
                  FROM {unicko} u
                  LEFT JOIN {unicko_room_users} ru ON (ru.roomid = u.id AND ru.userid = :userid)
                  JOIN {unicko_room_users} ruo ON (ruo.roomid = u.id AND ruo.affiliation = 0)
                  JOIN {user} owner ON (owner.id = ruo.userid)
                  WHERE
                    u.type = 1 AND
                    (ru.userid IS NOT NULL OR (:manager AND u.course = :courseid))";

        $params['userid']  = $USER->id;
        $params['manager'] = $manager;
        $params['courseid'] = $COURSE->id;
        $rooms = $DB->get_records_sql($sql, $params);
        foreach ($rooms as $room) {
            $url = $CFG->wwwroot . '/mod/unicko/room.php?id=' . $room->id;
            $name = $room->name . ' (' . $room->firstname . ' ' . $room->lastname . ')';
            $this->content->items[] = html_writer::tag('a', $name, array('href' => $url, 'target' => '_blank'));
            $this->content->icons[] = '';
        }

        if(!count($rooms)) {
            $this->content->items[] = '';
            $this->content->icons[] = '';
        }

        $configStr = get_string('managerooms', 'unicko');
        $configSrc = $OUTPUT->pix_url('i/settings');
        $addStr = get_string('createroom', 'unicko');
        $addSrc = $OUTPUT->pix_url('t/add');

        if(has_capability('mod/unicko:createprivateroom', $context)) {
            $addURL = $CFG->wwwroot . '/mod/unicko/new.php?course=' . $COURSE->id;
            $addEl = '<div><a href="' . $addURL . '" class="unicko-block-add"><img class="icon" title="' . $addStr .'" alt="'. $addStr . '" tabindex="0" src="'. $addSrc .'">' . $addStr . '</a></div>';
        } else {
            $addEl = '';
        }
        if(count($rooms)) {
            $configEl = '<div><a href="' . $CFG->wwwroot . '/mod/unicko/rooms.php" class="unicko-block-config"><img class="icon" title="' . $configStr .'" alt="'. $configStr . '" tabindex="0" src="'. $configSrc .'">' . $configStr . '</a></div>';
        } else {
            $configEl = '';
        }
        $this->content->footer =  $addEl . $configEl;

        return $this->content;
    }

    public function applicable_formats() {
        return array('all' => true);
    }

    public function instance_allow_multiple() {
        return false;
    }

    //function has_config() {return true;}

    /**
     * Returns the role that best describes this blocks contents.
     *
     * This returns 'navigation' as the blocks contents is a list of links to activities and resources.
     *
     * @return string 'navigation'
     */
    public function get_aria_role() {
        return 'navigation';
    }
}
