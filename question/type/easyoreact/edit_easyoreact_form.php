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
 * Defines the editing form for the easyoreact question type.
 *
 * @package    qtype
 * @subpackage easyoreact
 * @copyright  2007 Jamie Pratt
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/question/type/shortanswer/edit_shortanswer_form.php');

class qtype_easyoreact_edit_form extends qtype_shortanswer_edit_form {

    protected function definition_inner($mform) {
        global $PAGE, $CFG;
        $PAGE->requires->js('/question/type/easyoreact/easyoreact_script.js');
        $PAGE->requires->css('/question/type/easyoreact/easyoreact_styles.css');
        $mform->addElement('hidden', 'usecase', 1);
        $mform->setType('usecase', PARAM_INT);
        $mform->addElement('static', 'answersinstruct',
                get_string('correctanswers', 'qtype_easyoreact'),
                get_string('filloutoneanswer', 'qtype_easyoreact'));
        $mform->closeHeaderBefore('answersinstruct');
        $menu = array(
            get_string('caseproducts', 'qtype_easyoreact'),
            get_string('casereactants', 'qtype_easyoreact'),
            get_string('casereagents', 'qtype_easyoreact'));
        $mform->addElement('select', 'productsorreactants',
                get_string('caseproductsorreactants', 'qtype_easyoreact'), $menu);
        $easyoreactbuildstring = "\n<script LANGUAGE=\"JavaScript1.1\" SRC=\"../../marvin/marvin.js\"></script>".
            "<script LANGUAGE=\"JavaScript1.1\">
            msketch_name = \"MSketch\";
            msketch_begin(\"../../marvin\", 660, 335);
            msketch_param(\"background\", \"#ffffff\");
            msketch_param(\"autoscale\", \"true\");
            msketch_param(\"molbg\", \"#ffffff\");
            msketch_end();
            </script> ";

        // Output the marvin applet!
        $mform->addElement('html', html_writer::start_tag('div', array('style' => 'width:650px;')));
                $mform->addElement('html', html_writer::start_tag('div', array('style' => 'float: left;font-style: italic ;')));
                $mform->addElement('html', html_writer::start_tag('small'));
                $easyoreacthomeurl = 'http://www.chemaxon.com';
                $mform->addElement('html', html_writer::link($easyoreacthomeurl,
                    get_string('easyoreacteditor', 'qtype_easyoreact')));
                $mform->addElement('html', html_writer::empty_tag('br'));
                $mform->addElement('html', html_writer::tag('span', get_string('author', 'qtype_easyoreact'),
                    array('class' => 'easyoreactauthor')));
                $mform->addElement('html', html_writer::end_tag('small'));
                $mform->addElement('html', html_writer::end_tag('div'));
                $mform->addElement('html', $easyoreactbuildstring);
                $mform->addElement('html', html_writer::end_tag('div'));

                $jsmodule = array(
                    'name'     => 'qtype_easyoreact',
                    'fullpath' => '/question/type/easyoreact/easyoreact_script.js',
                    'requires' => array(),
                    'strings' => array(
                     array('enablejava', 'qtype_easyoreact')
                ));
                $PAGE->requires->js_init_call('M.qtype_easyoreact.insert_structure_into_applet',
                                      array(),
                                      true,
                                      $jsmodule);
                $this->add_per_answer_fields($mform, get_string('answerno', 'qtype_easyoreact', '{no}'),
                question_bank::fraction_options());
                $this->add_interactive_settings();
    }

    protected function get_per_answer_fields($mform, $label, $gradeoptions,
            &$repeatedoptions, &$answersoption) {
        $repeated = parent::get_per_answer_fields($mform, $label, $gradeoptions,
                $repeatedoptions, $answersoption);
                $scriptattrs = 'onClick = "getSmilesEdit(this.name, \'cxsmiles:u\')"';
        $insertbutton = $mform->createElement('button', 'insert', get_string('insertfromeditor', 'qtype_easyoreact'), $scriptattrs);
        array_splice($repeated, 2, 0, array($insertbutton));
        return $repeated;
    }

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        return $question;
    }

    public function qtype() {
        return 'easyoreact';
    }
}
