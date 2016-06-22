<?php
/**
 * Created a signed URL and redirect to the unicko room
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$roomid = required_param('id',PARAM_INT);

require_login();

$record = unicko_get_private_room($roomid, $USER->id);

$context = context_course::instance($record->course);
require_capability('mod/unicko:joinprivateroom', $context);
$host = has_capability('mod/unicko:accessallprivaterooms', $context);

if(isset($record->affiliation)) {
    $role = $host ? 'teacher' : 'student';
    $affiliation = $record->affiliation == 0 ? 'host' : 'member';
} else if($host) {
    $role = 'teacher';
    $affiliation = 'host';
} else {
    redirect(new moodle_url('/course/view.php', array('id'=>$record->course)));
}

if($record->allhosts) {
    $affiliation = 'host';
}

$course = $DB->get_record('course', array('id' => $record->course), '*', MUST_EXIST);

$cfg = get_config('unicko');
$message = $cfg->privateroommessage;

//add_to_log($unicko->id, 'unicko', 'view', "view.php?id={$unicko->id}", $unicko->name);

/// Print the page header

$PAGE->set_context($context);
$PAGE->set_url('/mod/unicko/room.php', array('id' => $record->id));
$PAGE->set_title(format_string($record->name));
$PAGE->set_heading(format_string($record->name));

$PAGE->set_cacheable(false);

$PAGE->requires->js( new moodle_url('/mod/unicko/module.js') );

// Output starts here
echo $OUTPUT->header();

if($message) {
    echo $OUTPUT->container($message, 'alert alert-warning', 'unicko-private-message');
    echo $OUTPUT->container('Redirecting to the room in 5 seconds...');
}

$data = array(
    "course_id" => $record->course,
    "course_name" => $course->shortname,
    "course_role" => $record->id,
    "room_id" => $roomid,
    "room_name" => $record->name . ' (' . $record->ownerfirstname . ' ' . $record->ownerlastname . ')',
    "room_lang" => $record->lang,
    "room_transient" => true,
    "room_affiliation" => $affiliation
);
echo unicko_get_join_form($data);

// Finish the page
echo $OUTPUT->footer();
