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
 * Form for editing sharing_cart block instances.
 *
 * @package   block_sharing_cart
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing sharing_cart block instances.
 *
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_sharing_cart_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;
        require_once($CFG->dirroot . '/cohort/lib.php');

        $context = context_course::instance($this->page->course->id);
        $cohorts = cohort_get_available_cohorts($context, COHORT_ALL);
        $cohortlist[-1] = get_string('myitems', 'block_sharing_cart');
        foreach ($cohorts as $cohort) {
            $cohortlist[$cohort->id] = $cohort->name;
        }

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        $mform->addElement('select', 'config_memberofcohort', get_string('memberofcohort', 'block_sharing_cart'), $cohortlist);
    }
}
