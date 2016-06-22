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
require_once(dirname(__FILE__).'/locallib.php');
require_once(dirname(__FILE__).'/delete_form.php');

require_login();

$roomid     = optional_param('id', -1, PARAM_INT);

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('deleteroom', 'unicko'));
$PAGE->set_heading(get_string('deleteroom', 'unicko'));
$PAGE->set_url('/mod/unicko/delete.php', array('id'=>$roomid));
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('rooms', 'unicko'), new moodle_url('/mod/unicko/rooms.php'));

$mform = new unicko_delete_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/mod/unicko/rooms.php'));
} else if ($data = $mform->get_data()) {
    $room = unicko_get_private_room($data->roomid, $USER->id);
    if($room->affiliation == 0) {
        $context = context_course::instance($room->course);
        require_capability('mod/unicko:createprivateroom', $context);
        $DB->delete_records('unicko', array('id'=>$data->roomid));
        $DB->delete_records('unicko_room_users', array('roomid'=>$data->roomid));
    } else {
        $context = context_course::instance($room->course);
        require_capability('mod/unicko:joinprivateroom', $context);
        $DB->delete_records('unicko_room_users', array('roomid'=>$data->roomid, 'userid'=>$USER->id));
    }
    redirect(new moodle_url('/mod/unicko/rooms.php'));
} else {
    $room = unicko_get_private_room($roomid, $USER->id);
    echo $OUTPUT->header();
    if($room->affiliation == 0) {
        echo get_string('deletecheckfull', '', $room->name);
    } else {
        echo get_string('leaveprivateroomcheck', 'unicko', $room->name);
    }
    $mform->set_data(array('roomid'=>$roomid));
    $mform->display();
}

echo $OUTPUT->footer();
