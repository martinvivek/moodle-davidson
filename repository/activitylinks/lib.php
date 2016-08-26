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
 * Repository plugin which allows linking to activites.
 *
 * @package    repository_activitylinks
 * @copyright  2013 Dan Poltawski <dan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/repository/lib.php');

class repository_activitylinks extends repository {

    public function get_listing($encodedpath = '', $page = '') {
        $ret = array();
        $ret['dynload'] = true;
        $ret['nosearch'] = true;
        $ret['nologin'] = true;
        $ret['list'] = array();

        if (!$courseid = $this->context->get_course_context(false)->instanceid) {
            return $ret;
        }

        $modinfo = get_fast_modinfo($courseid);

        $list = array();
        foreach ($modinfo->get_cms() as $cm) {
            if (!$cm->uservisible or !$cm->has_view()) {
                continue;
            }

            $list[] = array(
                'shorttitle' => $cm->name,
                'title' => $cm->name,
                'thumbnail' => $cm->get_icon_url()->out(false),
                'thumbnail_height' => 50,
                'thumbnail_width' => 50,
                'size' => '',
                'date' => '',
                'source' => $cm->get_url()->out(false)
            );
        }
        $ret['list'] = $list;

        return $ret;
    }

    public function supported_filetypes() {
        return array('link');
    }

    public function supported_returntypes() {
        return FILE_EXTERNAL;
    }
}
