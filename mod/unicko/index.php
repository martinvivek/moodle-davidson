<?php
/**
 * Displays the activity in a course
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = required_param('id', PARAM_INT);   // course

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

require_course_login($course);

//add_to_log($course->id, 'unicko', 'view all', 'index.php?id='.$course->id, '');

$coursecontext = context_course::instance($course->id);

$strname = get_string('modulenameplural', 'unicko');
$PAGE->set_url('/mod/unicko/index.php', array('id' => $id));
$PAGE->navbar->add($strname);
$PAGE->set_title("$course->shortname: $strname");
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('incourse');

echo $OUTPUT->header();
echo $OUTPUT->heading($strname);

if (! $unickos = get_all_instances_in_course('unicko', $course)) {
    notice(get_string('nounickos', 'unicko'), new moodle_url('/course/view.php', array('id' => $course->id)));
}

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($course->format == 'weeks') {
    $table->head  = array(get_string('week'), get_string('name'));
    $table->align = array('center', 'left');
} else if ($course->format == 'topics') {
    $table->head  = array(get_string('topic'), get_string('name'));
    $table->align = array('center', 'left', 'left', 'left');
} else {
    $table->head  = array(get_string('name'));
    $table->align = array('left', 'left', 'left');
}

foreach ($unickos as $unicko) {
    if (!$unicko->visible) {
        $link = html_writer::link(
            new moodle_url('/mod/unicko.php', array('id' => $unicko->coursemodule)),
            format_string($unicko->name, true),
            array('class' => 'dimmed'));
    } else {
        $link = html_writer::link(
            new moodle_url('/mod/unicko.php', array('id' => $unicko->coursemodule)),
            format_string($unicko->name, true));
    }

    if ($course->format == 'weeks' or $course->format == 'topics') {
        $table->data[] = array($unicko->section, $link);
    } else {
        $table->data[] = array($link);
    }
}

echo html_writer::table($table);

echo $OUTPUT->footer();
