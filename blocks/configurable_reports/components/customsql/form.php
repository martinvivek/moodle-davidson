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

/** Configurable Reports
  * A Moodle block for creating customizable reports
  * @package blocks
  * @author: Juan leyva <http://www.twitter.com/jleyvadelgado>
  * @date: 2009
  */

// Based on Custom SQL Reports Plugin
// See http://moodle.org/mod/data/view.php?d=13&rid=2884

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/formslib.php');

class customsql_form extends moodleform {

    function definition() {
        global $DB, $CFG, $COURSE;

        $mform =& $this->_form;

        $mform->addElement('textarea', 'querysql', get_string('querysql', 'block_configurable_reports'),
                'rows="35" cols="80"');
        $mform->addRule('querysql', get_string('required'), 'required', null, 'client');
        $mform->setType('querysql', PARAM_RAW);

        $mform->addElement('hidden','courseid',$COURSE->id);
        $mform->setType('courseid', PARAM_INT);

        $this->add_action_buttons();

        $mform->addElement('static', 'note', '', get_string('listofsqlreports', 'block_configurable_reports'));

// Disabled because of Davidson security preventing access to github.com
/*
        $userandrepo = get_config('block_configurable_reports','sharedsqlrepository');
        if (empty($userandrepo)) {
            $userandrepo = 'nadavkav/moodle-custom_sql_report_queries';
        }
        //$res = file_get_contents("https://api.github.com/repos/$userandrepo/contents/");
        $curl = curl_init("https://api.github.com/repos/$userandrepo/contents/");
        curl_setopt_array($curl, array(
            CURLOPT_PROXY => 'http://proxy.weizmann.ac.il',
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => 'Moodle cURL Request'
        ));
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res);

        $reportcategories = array(get_string('choose'));
        foreach ($res as $item) {
            if ($item->type == 'dir') $reportcategories[$item->path] = $item->path;
        }

        $mform->addElement('select', 'reportcategories', get_string('reportcategories', 'block_configurable_reports'), $reportcategories ,
            array('onchange'=>'M.block_configurable_reports.onchange_reportcategories(this,"'.sesskey().'")'));

        $mform->addElement('select', 'reportsincategory', get_string('reportsincategory', 'block_configurable_reports'), $reportcategories ,
            array('onchange'=>'M.block_configurable_reports.onchange_reportsincategory(this,"'.sesskey().'")'));

        $mform->addElement('textarea', 'remotequerysql', get_string('remotequerysql', 'block_configurable_reports'),
            'rows="15" cols="90"');
*/
        //$this->add_action_buttons();
    }

    function validation($data, $files) {
        global $DB, $CFG, $db, $USER;

        $errors = parent::validation($data, $files);

        $sql = $data['querysql'];
		$sql = trim($sql);

        // Disable security protection
        //$this->_customdata['report']->runstatistics = 1;
        if (empty($this->_customdata['report']->runstatistics) OR $this->_customdata['report']->runstatistics == 0) {
            // Simple test to avoid evil stuff in the SQL.
            //if (preg_match('/\b(ALTER|CREATE|DELETE|DROP|GRANT|INSERT|INTO|TRUNCATE|UPDATE|SET|VACUUM|REINDEX|DISCARD|LOCK)\b/i', $sql)) {
            // Allow cron SQL queries to run CREATE|INSERT|INTO queries.
            if (preg_match('/\b(ALTER|DELETE|DROP|GRANT|TRUNCATE|UPDATE|SET|VACUUM|REINDEX|DISCARD|LOCK)\b/i', $sql)) {
                $errors['querysql'] = get_string('notallowedwords', 'block_configurable_reports');
            }
        // Do not allow any semicolons.
        //} else if (strpos($sql, ';') !== false) {
        //    $errors['querysql'] = get_string('nosemicolon', 'report_customsql');

        // Make sure prefix is prefix_, not explicit.
        //} else if ($CFG->prefix != '' && preg_match('/\b' . $CFG->prefix . '\w+/i', $sql)) {
        //    $errors['querysql'] = get_string('noexplicitprefix', 'block_configurable_reports');

		// Now try running the SQL, and ensure it runs without errors.
        } else {

			$sql = $this->_customdata['reportclass']->prepare_sql($sql);
            $rs = $this->_customdata['reportclass']->execute_query($sql, 2);
            if (!$rs) {
                $errors['querysql'] = get_string('queryfailed', 'block_configurable_reports', $db->ErrorMsg());
            } else if (!empty($data['singlerow'])) {
                if (rs_EOF($rs)) {
                    $errors['querysql'] = get_string('norowsreturned', 'block_configurable_reports');
                }
            }

            if ($rs) {
                $rs->close();
            }
        }

        return $errors;
    }

}


