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

$roomid     = required_param('id', PARAM_INT);

// make sure the user is the room owner
$room = unicko_get_private_room_as_owner($roomid, $USER->id);

$course = $DB->get_record('course', array('id' => $room->course), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

require_login($course);

require_capability('mod/unicko:enrolprivateroom', $context);

$PAGE->set_url('/mod/unicko/enrol.php', array('id'=> $roomid));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($room->name);
$PAGE->set_heading($room->name);
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('rooms', 'unicko'), new moodle_url('/mod/unicko/rooms.php'));

$options1 = array('accesscontext' => $context, 'roomid' => $room->id, 'exclude' => array($USER->id));
$currentuserselector = new unicko_room_members('removeselect', $options1);
$existingMembers = $currentuserselector->find_users('');
$memberIDs = array();
foreach ($existingMembers as $group) {
    foreach ($group as $user) {
        $memberIDs[] = $user->id;
    }
}
$memberIDs[] = $USER->id;

$options2 = array('accesscontext' => $context, 'courseid' => $course->id, 'exclude' => $memberIDs);
$potentialuserselector = new unicko_course_user_selector('addselect', $options2);

// Process add and removes.
if (optional_param('add', false, PARAM_BOOL) && confirm_sesskey()) {
    $userstoassign = $potentialuserselector->get_selected_users();
    if (!empty($userstoassign)) {
        foreach($userstoassign as $adduser) {
            $record = new stdClass();
            $record->roomid = $room->id;
            $record->userid = $adduser->id;
            $record->affiliation = 1;
            $DB->insert_record('unicko_room_users', $record, false);
            // log
            $a = new stdClass();
            // Course info.
            $a->coursename      = $course->fullname;
            $a->courseshortname = $course->shortname;
            // Room info
            $a->roomname        = $room->name;
            $a->roomurl   = $CFG->wwwroot . '/mod/unicko/room.php?id=' . $room->id;
            $a->roomlink        = '<a href="' . $a->roomurl . '">' . format_string($room->name) . '</a>';
            //unicko_send_enrolment($USER, $adduser, $a);
        }
        redirect( new moodle_url('/mod/unicko/enrol.php', array('id'=> $roomid)) );
        $potentialuserselector->invalidate_selected_users();
        $currentuserselector->invalidate_selected_users();
    }
}

// Process incoming role unassignments.
if (optional_param('remove', false, PARAM_BOOL) && confirm_sesskey()) {
    $userstounassign = $currentuserselector->get_selected_users();
    if (!empty($userstounassign)) {
        foreach($userstounassign as $removeuser) {
            $DB->delete_records('unicko_room_users', array('roomid'=>$room->id, 'userid'=>$removeuser->id));
            // log
        }
        redirect( new moodle_url('/mod/unicko/enrol.php', array('id'=> $roomid)) );
        $potentialuserselector->invalidate_selected_users();
        $currentuserselector->invalidate_selected_users();
    }
}

echo $OUTPUT->header();

?>
<form id="assignform" method="post" action="<?php echo $PAGE->url ?>"><div>
    <input type="hidden" name="sesskey" value="<?php echo sesskey() ?>" />

    <table summary="" class="roleassigntable generaltable generalbox boxaligncenter" cellspacing="0">
        <tr>
            <td id="existingcell">
                <p><label for="removeselect"><?php print_string('roommembers', 'unicko'); ?></label></p>
                <?php $currentuserselector->display() ?>
            </td>
            <td id="buttonscell">
                <br/><br/>
                <div id="addcontrols">
                    <input name="add" id="add" type="submit" value="<?php echo $OUTPUT->larrow().'&nbsp;'.get_string('add'); ?>" title="<?php print_string('add'); ?>" /><br />
                </div>
                <br/><br/>
                <div id="removecontrols">
                    <input name="remove" id="remove" type="submit" value="<?php echo get_string('remove').'&nbsp;'.$OUTPUT->rarrow(); ?>" title="<?php print_string('remove'); ?>" />
                </div>
            </td>
            <td id="potentialcell">
                <p><label for="addselect"><?php print_string('coursestudents', 'unicko'); ?></label></p>
                <?php $potentialuserselector->display() ?>
            </td>
        </tr>
        <tr>
            <td><a class="btn" href="<?php echo new moodle_url('/mod/unicko/rooms.php'); ?>"><?php print_string('backtorooms', 'unicko'); ?></a></td>
            <td><a class="btn" href="<?php echo new moodle_url('/course/view.php', array('id' => $room->course)); ?>"><?php print_string('backtocourse', 'unicko'); ?></a></td>
        </tr>
    </table>
</div></form>
<?php

echo $OUTPUT->footer();
