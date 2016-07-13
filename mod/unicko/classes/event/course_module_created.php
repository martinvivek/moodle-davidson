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
 * The mod_unicko course module viewed event.
 * @package    mod_unicko
 */

namespace mod_unicko\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The mod_unicko course module viewed event class.
 *
 * @package    mod_unicko
 * @since      Moodle 2.9
 */
class course_module_created extends \core\event\course_module_created {

    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
        $this->data['objecttable'] = 'unicko';
    }

    public function get_description() {
        return "The user with id '$this->userid' created the '{$this->objecttable}' activity with " .
        "course module id '$this->contextinstanceid' (roomtype={$this->other['roomtype']}).";
    }
}
