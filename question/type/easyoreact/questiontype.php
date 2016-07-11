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
 * Question type class for the easyoreact question type.
 *
 * @package    qtype
 * @subpackage easyoreact
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/questionlib.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/question/type/easyoreact/question.php');
require_once($CFG->dirroot . '/question/type/shortanswer/questiontype.php');

/**
 * The easyoreact question type.
 *
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_easyoreact extends qtype_shortanswer {
    public function extra_question_fields() {
        return array('question_easyoreact', 'answers', 'productsorreactants');
    }

    public function questionid_column_name() {
        return 'question';
    }

    protected function initialise_question_instance(question_definition $question, $questiondata) {
        $questiondata->options->usecase = true;
        parent::initialise_question_instance($question, $questiondata);
    }
}
