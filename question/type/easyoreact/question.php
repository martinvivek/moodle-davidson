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
 * Marvin Molecular Editor question definition class.
 *
 * @package    qtype
 * @subpackage easyoreact
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/question/type/shortanswer/question.php');
class qtype_easyoreact_question extends qtype_shortanswer_question {
    public function compare_response_with_answer(array $response, question_answer $answer) {
        $char = array(".", ">");
        $ranswer = str_replace($char, ' ', trim($response['answer']));
        $aanswer = str_replace($char, ' ', trim($answer->answer));
        $ra = explode(" ", $ranswer);
        $aa = explode(" ", $aanswer);
        sort($ra);
        sort($aa);
        if ( count($ra) !== count($aa)) {
            return false;
        } else {
            sort($ra);
            sort($aa);
            if ($ra == $aa) {
                return true;
            } else {
                return false;
            }
        }

    }
    public function get_expected_data() {
        return array('answer' => PARAM_RAW, 'easyoreact' => PARAM_RAW, 'mol' => PARAM_RAW);
    }
}
