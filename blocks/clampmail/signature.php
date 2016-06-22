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
require_once('signature_form.php');

require_login();

$courseid = required_param('courseid', PARAM_INT);
$sigid = optional_param('id', 0, PARAM_INT);
$flash = optional_param('flash', 0, PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_INT);

if ($courseid and !$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('no_course', 'block_clampmail', '', $courseid);
}

$config = clampmail::load_config($courseid);

$context = context_course::instance($courseid);

$has_permission = (
    has_capability('block/clampmail:cansend', $context) or
    !empty($config['allowstudents'])
);

if (!$has_permission) {
    print_error('no_permission', 'block_clampmail');
}

$blockname = clampmail::_s('pluginname');
$header = clampmail::_s('signature');

$title = "{$blockname}: {$header}";

$PAGE->set_context($context);

$PAGE->set_course($course);
$PAGE->set_url('/blocks/clampmail/signature.php', array(
    'courseid' => $courseid, 'id' => $sigid
));

$PAGE->navbar->add($blockname);
$PAGE->navbar->add($header);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$PAGE->set_pagetype($blockname);
$PAGE->set_pagelayout('standard');

$params = array('userid' => $USER->id);
$dbsigs = $DB->get_records('block_clampmail_signatures', $params);

$sig = (!empty($sigid) and isset($sigs[$sigid])) ? $sigs[$sigid] : new stdClass;

if (empty($sigid) or !isset($dbsigs[$sigid])) {
    $sig = new stdClass;
    $sig->id = null;
    $sig->title = '';
    $sig->signature = '';
} else {
    $sig = $dbsigs[$sigid];
}

$sig->courseid = $courseid;
$sig->signatureformat = $USER->mailformat;

$options = array(
    'trusttext' => true,
    'subdirs' => true,
    'maxfiles' => EDITOR_UNLIMITED_FILES,
    'context' => $context
);

$sig = file_prepare_standard_editor($sig, 'signature', $options, $context,
    'block_clampmail', 'signature', $sig->id);

$form = new signature_form(null, array('signature_options' => $options));

if ($confirm) {
    $DB->delete_records('block_clampmail_signatures', array('id' => $sigid));
    redirect(new moodle_url('/blocks/clampmail/signature.php', array(
        'courseid' => $courseid,
        'flash' => 1
    )));
}

if ($form->is_cancelled()) {
    redirect(new moodle_url('/course/view.php', array('id' => $courseid)));
} else if ($data = $form->get_data()) {
    if (isset($data->delete)) {
        $delete = true;
    }

    if (empty($data->title)) {
        $warnings[] = clampmail::_s('required');
    }

    if (empty($warnings) and empty($delete)) {
        $data->signature = $data->signature_editor['text'];

        if (empty($data->default_flag)) {
            $data->default_flag = 0;
        }

        $params = array('userid' => $USER->id, 'default_flag' => 1);
        $default = $DB->get_record('block_clampmail_signatures', $params);

        if ($default and !empty($data->default_flag)) {
            $default->default_flag = 0;
            $DB->update_record('block_clampmail_signatures', $default);
        }

        if (!$default) {
            $data->default_flag = 1;
        }

        if (empty($data->id)) {
            $data->id = null;
            $data->id = $DB->insert_record('block_clampmail_signatures', $data);
        }

        // Persist relative links.
        $data = file_postupdate_standard_editor($data, 'signature', $options,
            $context, 'block_clampmail', 'signature', $data->id);

        $DB->update_record('block_clampmail_signatures', $data);

        $url = new moodle_url('signature.php', array(
            'id' => $data->id, 'courseid' => $course->id, 'flash' => 1
        ));
        redirect($url);
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading($header);

$first = array(0 => 'New '.clampmail::_s('sig'));
$only_names = function ($sig) {
    return ($sig->default_flag) ? $sig->title . ' (Default)': $sig->title;
};
$sig_options = $first + array_map($only_names, $dbsigs);

$form->set_data($sig);

if ($flash) {
    echo $OUTPUT->notification(get_string('changessaved'), 'notifysuccess');
}

if (!empty($delete)) {
    $msg = get_string('are_you_sure', 'block_clampmail', $sig);
    $confirm_url = new moodle_url('/blocks/clampmail/signature.php', array(
        'id' => $sig->id,
        'courseid' => $courseid,
        'confirm' => 1
    ));
    $cancel_url = new moodle_url('/blocks/clampmail/signature.php', array(
        'id' => $sig->id,
        'courseid' => $courseid
    ));
    echo $OUTPUT->confirm($msg, $confirm_url, $cancel_url);
} else {
    echo $OUTPUT->single_select('signature.php?courseid='.$courseid, 'id', $sig_options, $sigid);

    $form->display();
}

echo $OUTPUT->footer();
