<?php
/**
 * Created by The A Team on D date!
 * User: nthanna
 * Date: 7/28/11
 *  delete groupings (nadavkav 28-7-201)
 */
 
require_once '../config.php';
require_once $CFG->dirroot.'/group/lib.php';

// global $PAGE, $OUTPUT;
$strparticipants = get_string('participants');
$strgrouping     = get_string('grouping', 'group');
$strgroupings    = get_string('groupings', 'group');
$strgroupingsdelete    = get_string('groupingsdelete', 'group');
$courseid = required_param('courseid', PARAM_INT);

$PAGE->set_url('/group/groupings_delete.php', array('id'=>$courseid));

if (!$course = $DB->get_record('course', array('id'=> $courseid)) ) {
    print_error('nocourseid');
}
require_login($course);
$context = get_context_instance(CONTEXT_COURSE, $course->id);
require_capability('moodle/course:managegroups', $context);

//print_header();

navigation_node::override_active_url(new moodle_url('/group/grouping_delete.php', array('id'=>$courseid)));
$PAGE->navbar->add($strgroupingsdelete);

$PAGE->set_title($strgroupingsdelete);
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('standard');
echo $OUTPUT->header();
echo $OUTPUT->heading($strgroupingsdelete);

///  add these lines as course id wasn't transfered to this action  nadavkav  9/11/11
//$navlinks = array(array('name'=>$strparticipants, 'link'=>$CFG->wwwroot.'/user/index.php?id='.$courseid, 'type'=>'misc'),
//                  array('name'=>$strgroupings, 'link'=>'', 'type'=>'misc'));
// $navigation = $PAGE->navbar($navlinks);
//$OUTPUT->heading ($strgroupings, ': '.$strgroupings, $navigation, '', '', true, '', navmenu($course));

//$course = $DB->get_record('course', array('id'=>$group->courseid), '*', MUST_EXIST);
echo 'course name'.' '.$course->shortname.'<br/>';

$optionsyes = array('courseid'=>$courseid, 'groups'=>$groupids, 'sesskey'=>sesskey(), 'confirm'=>1, 'action'=>'delete');
$optionsno = array('id'=>$courseid);
$formcontinue = new single_button(new moodle_url('groupings_delete.php', $optionsyes), get_string('yes'), 'get');
$formcancel = new single_button(new moodle_url('groupings.php', $optionsno), get_string('no'), 'get');

if ($_GET['action'] == 'delete') {
    echo '<br/>'."not empty";
    foreach ($SESSION->groupingsids as $groupingid) {
        // $ok = delete_records('groupings','id',$groupingid); //deletes only record from table, we need to delete all that has to do with that grouping
        if (!groups_delete_grouping($groupingid)) {
            print_error('erroreditgrouping', 'group');
        }
        echo "Deleteing...".$groupingid."<br/>";
    }
    redirect('groupings.php?id='.$_GET['courseid'],5);
}

if (empty($_GET['action'])) {
    $SESSION->groupingsids = $_GET['groupingid'];
    //echo '<br/>'.'$SESSION->groupingsids'.' '.print_r( $SESSION->groupingsids);
    foreach ($SESSION->groupingsids as $groupings) {
        $groupings_item = $DB->get_record('groupings',array('id'=>$groupings));
        echo get_string('abouttodeletegroupings','core_davidson',$groupings_item->name);
    }
//    echo $OUTPUT->confirm(get_string('deletegroupingconfirm','group',''),'groupings_delete.php?action=delete&courseid='.$_POST['courseid'],'groupings.php?id='.$_POST['courseid']);
    echo $OUTPUT->confirm(get_string('deletegroupingconfirm','group',''),$formcontinue,$formcancel);
}


echo $OUTPUT->footer();
