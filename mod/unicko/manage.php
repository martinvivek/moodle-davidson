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

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$u  = optional_param('u', 0, PARAM_INT);  // unicko_activity instance ID

if ($id) {
    $cm         = get_coursemodule_from_id('unicko', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    unicko_update_unicko_times($cm->instance);
    $unicko     = $DB->get_record('unicko', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($u) {
    unicko_update_unicko_times($u);
    $unicko     = $DB->get_record('unicko', array('id' => $u), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $unicko->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('unicko', $unicko->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = context_module::instance($cm->id);

if(!has_capability('mod/unicko:manage', $context)) {
    $url = new moodle_url('/mod/unicko/view.php', array('id' => $cm->id));
    redirect($url);
}

//add_to_log($course->id, 'unicko', 'manage', "manage.php?id={$cm->id}", $unicko->name, $cm->id);

/// Print the page header

$PAGE->set_context($context);
$PAGE->set_url('/mod/unicko/mange.php', array('id' => $cm->id));
$PAGE->set_title(format_string($unicko->name));
$PAGE->set_heading(format_string($course->fullname));

$PAGE->set_cacheable(false);

// Output starts here
echo $OUTPUT->header();

echo $OUTPUT->heading('Redirecting to the room settings');

$roomid = (string) $unicko->id;
$jsurl = new moodle_url('/mod/unicko/module.js');
$PAGE->requires->js($jsurl);

$host = has_capability('mod/unicko:host', $context);
$affiliation = $host ? 'host' : 'member';

$data = array(
    "course_id" => $unicko->course,
    "course_name" => $course->shortname,
    "course_role" => $host ? 'teacher' : 'student',
    "room_id" => $roomid,
    "room_name" => $unicko->name,
    "room_lang" => $unicko->lang,
    "room_affiliation" => $affiliation
);
echo unicko_get_manage_form($data);

// Finish the page
echo $OUTPUT->footer();
