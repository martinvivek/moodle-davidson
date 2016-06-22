<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   block_clampmail
 * @copyright 2013 Collaborative Liberal Arts Moodle Project
 * @copyright 2012 Louisiana State University (original Quickmail block)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('lib.php');

require_login();

$courseid = required_param('courseid', PARAM_INT);
$type = optional_param('type', 'log', PARAM_ALPHA);
$typeid = optional_param('typeid', 0, PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHA);
$page = optional_param('page', 0, PARAM_INT);
$perpage = optional_param('perpage', 10, PARAM_INT);
$userid = optional_param('userid', $USER->id, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('no_course', 'block_clampmail', '', $courseid);
}

$context = context_course::instance($courseid);

// Has to be in on of these.
if (!in_array($type, array('log', 'drafts'))) {
    print_error('not_valid', 'block_clampmail', '', $type);
}

$canimpersonate = has_capability('block/clampmail:canimpersonate', $context);
if (!$canimpersonate and $userid != $USER->id) {
    print_error('not_valid_user', 'block_clampmail');
}

$config = clampmail::load_config($courseid);

$valid_actions = array('delete', 'confirm');

$can_send = has_capability('block/clampmail:cansend', $context);

$proper_permission = ($can_send or !empty($config['allowstudents']));

$can_delete = ($can_send or ($proper_permission and $type == 'drafts'));

// Stops students from tempering with history.
if (!$proper_permission or (!$can_delete and in_array($action, $valid_actions))) {
    print_error('no_permission', 'block_clampmail');
}

if (isset($action) and !in_array($action, $valid_actions)) {
    print_error('not_valid_action', 'block_clampmail', '', $action);
}

if (isset($action) and empty($typeid)) {
    print_error('not_valid_typeid', 'block_clampmail', '', $action);
}

$blockname = clampmail::_s('pluginname');
$header = clampmail::_s($type);

$PAGE->set_context($context);
$PAGE->set_course($course);
$PAGE->navbar->add($blockname);
$PAGE->navbar->add($header);
$PAGE->set_title($blockname . ': ' . $header);
$PAGE->set_heading($blockname . ': ' . $header);
$PAGE->set_url('/course/view.php', array('id' => $courseid));
$PAGE->set_pagetype($blockname);
$PAGE->set_pagelayout('standard');

$dbtable = 'block_clampmail_' . $type;

$params = array('userid' => $userid, 'courseid' => $courseid);
$count = $DB->count_records($dbtable, $params);

switch ($action) {
    case "confirm":
        if (clampmail::cleanup($dbtable, $context->id, $typeid)) {
            $url = new moodle_url('/blocks/clampmail/emaillog.php', array(
                'courseid' => $courseid,
                'type' => $type
            ));
            redirect($url);
        } else {
            print_error('delete_failed', 'block_clampmail', '', $typeid);
        }
    case "delete":
        $html = clampmail::delete_dialog($courseid, $type, $typeid);
        break;
    default:
        $html = clampmail::list_entries($courseid, $type, $page, $perpage, $userid, $count, $can_delete);
}

if ($canimpersonate and $USER->id != $userid) {
    $user = $DB->get_record('user', array('id' => $userid));
    $header .= ' for '. fullname($user);
}

echo $OUTPUT->header();
echo $OUTPUT->heading($header);

if ($canimpersonate) {
    $sql = "SELECT DISTINCT(l.userid), u.firstname, u.lastname
                FROM {block_clampmail_$type} l,
                     {user} u
                WHERE u.id = l.userid AND courseid = ? ORDER BY u.lastname";
    $users = $DB->get_records_sql($sql, array($courseid));

    $user_options = array_map(function($user) { return fullname($user); }, $users);

    $url = new moodle_url('emaillog.php', array(
        'courseid' => $courseid,
        'type' => $type
    ));

    $default_option = array('' => clampmail::_s('select_users'));

    echo $OUTPUT->single_select($url, 'userid', $user_options, $userid, $default_option);
}

if (empty($count)) {
    echo $OUTPUT->notification(clampmail::_s('no_'.$type));

    echo $OUTPUT->continue_button('/blocks/clampmail/email.php?courseid='.$courseid);

    echo $OUTPUT->footer();
    exit;
}

echo $html;

echo $OUTPUT->footer();
