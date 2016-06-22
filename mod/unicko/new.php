<?php
/**
 * Created a signed URL and redirect to the unicko room
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/new_form.php');

require_login();

$courseid = optional_param('course', 0, PARAM_INT);
if($courseid) {
    require_login($courseid);
    $PAGE->set_url(new moodle_url('/mod/unicko/new.php', array('course'=>$courseid)));
    $PAGE->navbar->ignore_active();
    $PAGE->navbar->add(get_string('rooms', 'unicko'), new moodle_url('/mod/unicko/rooms.php'));
} else {
    $PAGE->set_context(context_system::instance());
    $PAGE->set_url(new moodle_url('/mod/unicko/new.php'));
}

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('createroom', 'unicko'));
$PAGE->set_heading(get_string('createroom', 'unicko'));

$mform = new unicko_new_form(null, array('course'=>$courseid));

if ($mform->is_cancelled()) {
    $data = $mform->get_data();
    redirect(new moodle_url('/course/view.php', array('id'=>$courseid)));
} else if ($fromform = $mform->get_data()) {
    $data = $mform->get_data();
    $context = context_course::instance($data->course);
    require_capability('mod/unicko:createprivateroom', $context);
    $count = $DB->count_records('unicko_room_users', array('userid'=>$USER->id));
    if($count >= 10) {
        echo $OUTPUT->header();
        echo get_string('toomanyrooms', 'unicko');
        $mform->display();
        echo $OUTPUT->footer();
    } else {
        $record = new stdClass();
        $record->type = 1; // private room
        $record->course = $data->course;
        $record->name = $data->name;
        $record->lang = $data->lang;
        $record->textdir = ($data->lang == 'he' || $data->lang == 'ar') ? 1 : 0;
        $record->allhosts = isset($data->allhosts) ? $data->allhosts : 0;
        $record->timecreated = time();
        $roomid = $DB->insert_record('unicko', $record, true);

        $record = new stdClass();
        $record->roomid = $roomid;
        $record->userid = $USER->id;
        $record->affiliation = 0;
        $DB->insert_record('unicko_room_users', $record, false);
        redirect(new moodle_url('/mod/unicko/enrol.php', array('id'=>$roomid)));
    }
} else {
    $context = context_system::instance();
    $PAGE->set_context($context);
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();
}
