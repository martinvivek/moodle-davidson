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
 * Atto text editor integration version file.
 *
 * @package    atto_cloze
 * @copyright  2016 onward Daniel Thies <dthies@ccal.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Return the order this plugin should be displayed in the toolbar
 * @return int the absolute position within the toolbar
 */

function atto_cloze_strings_for_js() {
    global $PAGE;

    $PAGE->requires->strings_for_js(array( 'pluginname' ), 'atto_cloze' );
    $PAGE->requires->strings_for_js(array( 'common:insert' ), 'editor_tinymce' );
    $PAGE->requires->strings_for_js(array( 'chooseqtypetoadd' ), 'question' );
    $PAGE->requires->strings_for_js(array( 'defaultmark' ), 'core_question' );
    $PAGE->requires->strings_for_js(array( 'multichoice', 'numerical', 'shortanswer' ), 'mod_quiz' );
    $PAGE->requires->strings_for_js(array( 'addmoreanswerblanks', 'tolerance' ), 'qtype_calculated' );
    $PAGE->requires->strings_for_js(array( 'add', 'answer', 'cancel', 'delete',
            'duplicate', 'feedback', 'grade', 'previous' ), 'core' );
}

function atto_cloze_params_for_js() {
    global $CFG;
    require($CFG->dirroot . '/version.php');

    $selectinline = array('option' => get_string('layoutselectinline', 'qtype_multianswer'));
    $horizontal = array('option' => get_string('layouthorizontal', 'qtype_multianswer'));
    $vertical = array('option' => get_string('layoutvertical', 'qtype_multianswer'));
    $qtypes = array(
        array('type' => 'MULTICHOICE', 'name' => get_string('multichoice', 'mod_quiz'),
            'summary' => get_string('pluginnamesummary', 'qtype_multichoice'),
            'options' => array($selectinline)
        ),
        array('type' => 'MULTICHOICE_H', 'name' => get_string('multichoice', 'mod_quiz'),
            'summary' => get_string('pluginnamesummary', 'qtype_multichoice'),
            'options' => array($horizontal)
        ),
        array('type' => 'MULTICHOICE_V', 'name' => get_string('multichoice', 'mod_quiz'),
            'summary' => get_string('pluginnamesummary', 'qtype_multichoice'),
            'options' => array($vertical)
        ),
    );
    // Check whether shuffled multichoice is supported yet.
    if ($version >= 2015111604) {
        $shuffle = array('option' => get_string('shufflewithin', 'mod_quiz'));
        $qtypes = array_merge($qtypes, array(

            array('type' => 'MULTICHOICE_S', 'name' => get_string('multichoice', 'mod_quiz'),
                'summary' => get_string('pluginnamesummary', 'qtype_multichoice'),
                'options' => array($selectinline, $shuffle)
            ),
            array('type' => 'MULTICHOICE_HS', 'name' => get_string('multichoice', 'mod_quiz'),
                'summary' => get_string('pluginnamesummary', 'qtype_multichoice'),
                'options' => array($horizontal, $shuffle)
            ),
            array('type' => 'MULTICHOICE_VS', 'name' => get_string('multichoice', 'mod_quiz'),
                'summary' => get_string('pluginnamesummary', 'qtype_multichoice'),
                'options' => array($vertical, $shuffle)
            ),
        ));
    }
    $qtypes = array_merge($qtypes, array(
        array('type' => 'NUMERICAL', 'name' => get_string('numerical', 'mod_quiz'),
        'summary' => get_string('pluginnamesummary', 'qtype_numerical')),
        array('type' => 'SHORTANSWER', 'name' => get_string('shortanswer', 'mod_quiz'),
        'summary' => get_string('pluginnamesummary', 'qtype_shortanswer')),
    ));
    return array('questiontypes' => $qtypes);
}
