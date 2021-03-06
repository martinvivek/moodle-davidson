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
$group = optional_param('group', 0, PARAM_INT);

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
require_capability('mod/unicko:join', $context);

//add_to_log($course->id, 'unicko', 'view', "view.php?id={$cm->id}", $unicko->name, $cm->id);
// add event  hanna 17/2/16
$params = array(
    'objectid' => $unicko->id,
    'context' => $context
);

$event = \mod_unicko\event\course_module_viewed::create($params);
//$event->add_record_snapshot('unicko', $unicko);
$event->trigger();

/// Print the page header

$PAGE->set_context($context);
$PAGE->set_url('/mod/unicko/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($unicko->name));
$PAGE->set_heading(format_string($course->fullname));

$PAGE->set_cacheable(false);

// Mark viewed and trigger the course_module_viewed event.
unicko_view($unicko, $course, $cm, $context);

// Output starts here
echo $OUTPUT->header();

echo $OUTPUT->heading('Redirecting to the room');

$roomid = (string) $unicko->id;
$jsurl = new moodle_url('/mod/unicko/module.js');
//  add groups  nadavkav 21/2/16
if($group) {
    // Check access.
    if (groups_get_activity_groupmode($cm)) {
        $groups = groups_get_activity_allowed_groups($cm);
    } else {
        $groups = array();
    }
    if (in_array($group, array_keys($groups))) {
        $roomid = "{$unicko->id}-{$group}";
        $PAGE->requires->js($jsurl);
    } else {
        print_error('groupnotamember', 'group');
    }
} else {
    if (groups_get_activity_groupmode($cm) == 0) { // No groups mode
        $PAGE->requires->js($jsurl);
    } else {
        if($cm->groupingid) {
            $groups = groups_get_activity_allowed_groups($cm);
            $groupingGroups = groups_get_all_groups($course->id, 0, $cm->groupingid);
            if(count(array_intersect_key($groups, $groupingGroups))) {
                $roomid = "{$unicko->id}_{$cm->groupingid}";
                $PAGE->requires->js($jsurl);
            } else {
                print_error('groupnotamember', 'group');
            }
        } else {
            $groups = groups_get_activity_allowed_groups($cm);
            switch(count($groups)) {
                case 0:
                    print_error('groupnotamember', 'group');
                    break;
                case 1:
                    $group = key($groups);
                    $roomid = "{$unicko->id}-{$group}";
                    $PAGE->requires->js($jsurl);
                    break;
                default:
                    $PAGE->requires->js($jsurl);
                    groups_print_activity_menu($cm, $CFG->wwwroot . "/mod/unicko/view.php?id=$cm->id", false, true);
                    $joinStr = get_string('join', 'unicko');
                    print '<button id="unicko-join" class="btn">'.$joinStr.'</button>';
            }
        }
    }
}

$host = has_capability('mod/unicko:host', $context);
$affiliation = $host ? 'host' : 'member';
if($unicko->allhosts) {
    $affiliation = 'host';
}

$data = array(
    "course_id" => $unicko->course,
    "course_name" => $course->shortname,
    "course_role" => $host ? 'teacher' : 'student',
    "room_id" => $roomid,
    "room_name" => $unicko->name,
    "room_lang" => $unicko->lang,
    "room_affiliation" => $affiliation
);
echo unicko_get_join_form($data);

// Finish the page
echo $OUTPUT->footer();
