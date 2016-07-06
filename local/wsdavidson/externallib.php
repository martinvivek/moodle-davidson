<?php

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
 * External Web Service Template
 *
 * @package    localwsdavidson
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

class local_wsdavidson_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function hello_world_parameters() {
        return new external_function_parameters(
                array('welcomemessage' => new external_value(PARAM_TEXT, 'The welcome message. By default it is "Hello world,"', VALUE_DEFAULT, 'Hello world, '))
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
    public static function hello_world($welcomemessage = 'Hello world, ') {
        global $USER;

        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::hello_world_parameters(),
                array('welcomemessage' => $welcomemessage));

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }

        return $params['welcomemessage'] . $USER->firstname ;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function hello_world_returns() {
        return new external_value(PARAM_TEXT, 'The welcome message + user first name');
    }


    /////////////////////////////////////////////////////////

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function unenroll_user_from_course_parameters() {
        return new external_function_parameters(
            array('useridnumber' => new external_value(PARAM_TEXT, ' User ID number - "Mispar Zehot" '),
                  'courseidnumber' => new external_value(PARAM_TEXT, ' Moodle Course ID '))
        );
    }

    public static function unenroll_user_from_course($useridnumber, $courseidnumber) {
        global $USER, $DB;

        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::unenroll_user_from_course_parameters(),
            array('useridnumber' => $useridnumber, 'courseidnumber' => $courseidnumber));

        //Context validation
        //OPTIONAL but in most web service it should present

        //$context = get_context_instance(CONTEXT_USER, $USER->id);
        $context = context_user::instance($USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }

        $user = $DB->get_record('user', array('idnumber'=>$useridnumber));
        $course = $DB->get_record('course', array('idnumber'=>$courseidnumber));

        $e = $DB->get_record('enrol', array('enrol'=>'manual', 'courseid' => $course->id), '*', MUST_EXIST);
        $ue = $DB->get_record('user_enrolments', array('enrolid' => $e->id, 'userid'=> $user->id), '*', MUST_EXIST);
        //$instance = $DB->get_record('enrol', array('id'=>$ue->enrolid), '*', MUST_EXIST);
        $plugin = enrol_get_plugin($e->enrol);
        $plugin->unenrol_user($e, $ue->userid);

        //if (!$plugin->allow_unenrol_user($instance, $ue) or !has_capability("enrol/$instance->enrol:unenrol", $context)) {
        //    print_error('erroreditenrolment', 'enrol');
        //}

        //$roleid = 5;
        //$coursecontext = context_course::instance($course->id);
        //role_unassign($roleid, $user->id, $coursecontext->id);

        //return "UserID={$user->id} was unassigned from course {$course->fullname}.";
        add_to_log($course->id, 'course', 'unenrol webservice', '../enrol/users.php?id='.$course->id, $course->id);
        return 'Ok';
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function unenroll_user_from_course_returns() {
        return new external_value(PARAM_TEXT, 'The welcome message + user first name (based on user ID number');
    }




    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function unsuspend_user_from_course_parameters() {
        return new external_function_parameters(
            array('useridnumber' => new external_value(PARAM_TEXT, ' User ID number - "Mispar Zehot" '),
                'courseidnumber' => new external_value(PARAM_TEXT, ' Moodle Course ID '))
        );
    }

    public static function unsuspend_user_from_course($useridnumber, $courseidnumber) {
        global $USER, $DB;

        //Parameter validation
        //REQUIRED
        $params = self::validate_parameters(self::unsuspend_user_from_course_parameters(),
            array('useridnumber' => $useridnumber, 'courseidnumber' => $courseidnumber));

        //Context validation
        //OPTIONAL but in most web service it should present

        //$context = get_context_instance(CONTEXT_USER, $USER->id);
        $context = context_user::instance($USER->id);
        self::validate_context($context);

        //Capability checking
        //OPTIONAL but in most web service it should present
        if (!has_capability('moodle/user:viewdetails', $context)) {
            throw new moodle_exception('cannotviewprofile');
        }
        //add_to_log('1', 'course', 'unsuspend webservice', 'user_idnumber='.$useridnumber.' | '.'course_idnumber'.$courseidnumber,'ws');

        $user = $DB->get_record('user', array('idnumber'=>$useridnumber));
        $course = $DB->get_record('course', array('idnumber'=>$courseidnumber));

        $e = $DB->get_record('enrol', array('enrol'=>'manual', 'courseid' => $course->id), '*', MUST_EXIST);
        $ue = $DB->get_record('user_enrolments', array('enrolid' => $e->id, 'userid'=> $user->id), '*', MUST_EXIST);
        //$instance = $DB->get_record('enrol', array('id'=>$ue->enrolid), '*', MUST_EXIST);
        $plugin = enrol_get_plugin($e->enrol);
        //$plugin->unenrol_user($e, $ue->userid);
        $plugin->update_user_enrol($e, $ue->userid, '0');

        //if (!$plugin->allow_unenrol_user($instance, $ue) or !has_capability("enrol/$instance->enrol:unenrol", $context)) {
        //    print_error('erroreditenrolment', 'enrol');
        //}

        //$roleid = 5;
        //$coursecontext = context_course::instance($course->id);
        //role_unassign($roleid, $user->id, $coursecontext->id);

        //return "UserID={$user->id} was unassigned from course {$course->fullname}.";
        add_to_log($course->id, 'course', 'unsuspend webservice', '../enrol/users.php?id='.$course->id, $course->id);

        return 'Ok';
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function unsuspend_user_from_course_returns() {
        return new external_value(PARAM_TEXT, 'The welcome message + user first name (based on user ID number');
    }

}
