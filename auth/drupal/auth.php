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
 * Authentication Plugin: drupal Authentication
 * Just does a simple check against the moodle database.
 *
 * @package    auth
 * @subpackage drupal  (was copied from auth/manual)
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');
require_once($CFG->dirroot.'/group/lib.php');

/**
 * drupal authentication plugin.
 *
 * @package    auth
 * @subpackage drupal
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class auth_plugin_drupal extends auth_plugin_base {

    /**
     * The name of the component. Used by the configuration.
     */
    const COMPONENT_NAME = 'auth/drupal';

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'drupal';
        $this->config = get_config(self::COMPONENT_NAME);
    }

    /**
     * Constructor.
     */
    function auth_plugin_drupal() {
        debugging('Use of class name as constructor is deprecated', DEBUG_DEVELOPER);
        self::__construct();
    }

    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist. (Non-mnet accounts only!)
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    function user_login($username, $password) {
        global $CFG, $DB, $USER;
        if (!$user = $DB->get_record('user', array('username'=>$username, 'mnethostid'=>$CFG->mnet_localhost_id))) {
            return false;
        }
        if (!validate_internal_user_password($user, $password)) {
            return false;
        }
        if ($password === 'changeme') {
            // force the change - this is deprecated and it makes sense only for drupal auth,
            // because most other plugins can not change password easily or
            // passwords are always specified by users
            set_user_preference('auth_forcepasswordchange', true, $user->id);
        }
        return true;
    }

    /**
     * Updates the user's password.
     *
     * Called when the user password is updated.
     *
     * @param  object  $user        User table object
     * @param  string  $newpassword Plaintext password
     * @return boolean result
     */
    function user_update_password($user, $newpassword) {
        $user = get_complete_user_data('id', $user->id);
        set_user_preference('auth_drupal_passwordupdatetime', time(), $user->id);
        // This will also update the stored hash to the latest algorithm
        // if the existing hash is using an out-of-date algorithm (or the
        // legacy md5 algorithm).
        return update_internal_user_password($user, $newpassword);
    }

    function prevent_local_passwords() {
        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
        return true;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return true;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    function change_password_url() {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password() {
        return true;
    }

    /**
     * Returns true if plugin can be manually set.
     *
     * @return bool
     */
    function can_be_manually_set() {
        return true;
    }

    /**
     * Prints a form for configuring this authentication plugin.
     *
     * This function is called from admin/auth.php, and outputs a full page with
     * a form for configuring this plugin.
     *
     * @param array $config An object containing all the data for this page.
     * @param string $error
     * @param array $user_fields
     * @return void
     */
    function config_form($config, $err, $user_fields) {
        include 'config.html';
    }

    /**
     * Return number of days to user password expires.
     *
     * If user password does not expire, it should return 0 or a positive value.
     * If user password is already expired, it should return negative value.
     *
     * @param mixed $username username (with system magic quotes)
     * @return integer
     */
    public function password_expire($username) {
        $result = 0;

        if (!empty($this->config->expirationtime)) {
            $user = core_user::get_user_by_username($username, 'id,timecreated');
            $lastpasswordupdatetime = get_user_preferences('auth_manual_passwordupdatetime', $user->timecreated, $user->id);
            $expiretime = $lastpasswordupdatetime + $this->config->expirationtime * DAYSECS;
            $now = time();
            $result = ($expiretime - $now) / DAYSECS;
            if ($expiretime > $now) {
                $result = ceil($result);
            } else {
                $result = floor($result);
            }
        }

        return $result;
    }

    /**
     * Processes and stores configuration data for this authentication plugin.
     *
     * @param stdClass $config
     * @return void
     */
    function process_config($config) {
        // Set to defaults if undefined.
        if (!isset($config->expiration)) {
            $config->expiration = '';
        }
        if (!isset($config->expiration_warning)) {
            $config->expiration_warning = '';
        }
        if (!isset($config->expirationtime)) {
            $config->expirationtime = '';
        }

        // Save settings.
        set_config('expiration', $config->expiration, self::COMPONENT_NAME);
        set_config('expiration_warning', $config->expiration_warning, self::COMPONENT_NAME);
        set_config('expirationtime', $config->expirationtime, self::COMPONENT_NAME);
        return true;
    }

    function user_exists($username) {
        global $DB;
        return $DB->get_record('user', array('username' => $username));
    }

    function user_create($user, $plainslashedpassword) {

    }
    function randomPassword() {
        $alphabet = "0123456789";
        $pass = array(); //remember to declare $pass as an array
        $pass[] = "a";
        $pass[] = "a";
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 6; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    /**
     * Sign up a new user .
     * Password is passed in plaintext.
     * @param object $user new user object
     * @param boolean $notify print notice with link and terminate
     */
    function user_signup($user, $notify=false) {
        global $CFG, $DB, $USER;

        require_once($CFG->dirroot.'/user/profile/lib.php');

            if ($user->lang == 2) { $user->lang = 'en'; }  //  if case of form mistakes....  hanna 29/2/16

         if ($user->lang == 'he' or $user->lang == 'ar' ) {
            $user->username = $user->idnumber;
             $peg = 'http://pegasus1.weizmann.ac.il/moodle2/';
        //    $user->password = $this->randomPassword();
        //    $user->url = $user->password;
        } else { // some other country, probably not Israel
            $user->username = $user->email;
             $peg = 'http://pegasus1.weizmann.ac.il/moodle2/?lang=en';
        //    $user->password = $this->randomPassword();
        //    $user->url = $user->password;
        }
        $user->maildisplay = 0;  // hide email from everybody - security.  hanna 1/9/13
        // hash (md5) the password
    //    $plainslashedpassword = $user->password;
        //unset($user->password);
        if (!$this->user_exists($user->username)) {     // new user
            // create a new user
            $user->password = $this->randomPassword();
            $user->url = $user->password;
            $plainslashedpassword = $user->password;

            $user->id = $DB->insert_record('user', $user);
            update_internal_user_password($user, $plainslashedpassword);
            // Create USER context for this user.
            $usercontext = context_user::instance($user->id);

            $event = \core\event\user_created::create(
                array(
                    'objectid' => $user->id,
                    'relateduserid' => $user->id,
                    'context' => $usercontext
                )
            );
            $event->trigger();
            // Save any custom profile field information
            profile_save_data($user);

            //   send_confirm_email($user);  die; ///////////  only for trying delete it
        } else { // returning user
            // get current user's id
            $existinguser = $DB->get_record('user', array('username'=>$user->username));
            $user->id = $existinguser->id;
            if (!$existinguser->url) {
                //throw new \coding_exception('The \'relateduserid\' must be set.');
                notice("Missing password, please call us");
            }
            $plainslashedpassword = $existinguser->url;
            // update user's info
            $ok = $DB->update_record('user', $user);


            $supportuser = get_admin();
            $site = get_string('oursitename','core_davidson',null,false,$user->lang);
            $subject = get_string('emailconfirmationsubject', '', $site ,false,$user->lang);
            $message     = get_string('emailconfirmation', 'auth_drupal', null,false,$user->lang) . '<br/>'  ;
            $browseinfo = '<br/>' . get_string('usebrowser', 'auth_drupal', null, false, $user->lang) . '<br/>' ;
            $forgotpss = get_string('forgotpass', 'auth_drupal', null,false,$user->lang) . '  ' .  'https://pegasus1.weizmann.ac.il/moodle2/login/forgot_password.php' . '<br/>' . '<br/>';
            $umessage = get_string('yourusername', 'auth_drupal', null,false,$user->lang) . '<br/>' . $user->username . '<br/>';
        //    $smessage = get_string('siteaddrss', 'auth_drupal', null,false,$user->lang)  . '<br/>'  . 'http://pegasus1.weizmann.ac.il/moodle2/';
        // site in user's lang   hanna 29/2/16
            $smessage = get_string('siteaddrss', 'auth_drupal', null,false,$user->lang)  . '<br/>'  . $peg ;
            $fullmessage =  fullname( $user) . '<br/>' . $message . '<br/>'. $umessage . '<br/>' . $browseinfo . '<br/>' . $forgotpss . '<br/>' . $smessage ;
            $messagehtml = text_to_html(get_string('emailconfirmation', 'auth_drupal', null,false,$user->lang ), false, false, true) . '<br/>'  ;
            $fullmessagehtml = fullname($user) . '<br/>' .$messagehtml . '<br/>'. $umessage . '<br/>' . $browseinfo . '<br/>' . $forgotpss . $smessage ;
            $user->mailformat = 1;  // Always send HTML version as well
            email_to_user($user, $supportuser, $subject, $fullmessage, $fullmessagehtml);
            email_to_user($supportuser, $supportuser, $subject, $fullmessage . '<br/>' . 'sid' . '<br/>' . $user->sid . '<br/>' , $fullmessagehtml . '<br/>' . 'sid' . '<br/>' . $user->sid . '<br/>' );
            /*
            if (! email_to_user($user, $supportuser, $subject, $fullmessage, $fullmessagehtml)) {
                    print_error('noemail', 'auth_drupal');
             }
             */

         //   if
    //   send_confirm_email($user);  // {  //  email to returning user  hanna 1/6/15
         //        print_error('noemail', 'auth_drupal');
         //   }
            $usercontext = context_user::instance($user->id);
            $event = \core\event\user_returned::create(
                array(
                    'objectid' => $user->id,
                    'relateduserid' => $user->id,
                    'context' => $usercontext
                )
            );
            $event->trigger();
        }
        if ($user->birthday) {  //  hanna 27/7/14
            $userinfofield = $DB->get_record('user_info_field', array('shortname' => 'birthday'));
            if (!$DB->get_record('user_info_data', array('fieldid'=> $userinfofield->id, 'userid' => $user->id) )) {
                $userinfodata_record = new stdClass();
                $userinfodata_record->fieldid = $userinfofield->id;
                $userinfodata_record->userid = $user->id;
                date_default_timezone_set('Asia/Jerusalem');
                $userinfodata_record->data = strtotime($user->birthday);
                $userinfodata_record->dataformat = 0;
                $result = $DB->insert_record('user_info_data', $userinfodata_record);
            }
        }

        set_user_preference('messagesdisabled',$user->messagesdisabled,$user->id);
        // *****   add option to enrol into multiple courses
        foreach ($user->moodlecourseid as $moodlecourse) {
            if (!empty($user->course_idnumbers)) {
                $enrolledcourse = $DB->get_record('course', array('idnumber'=>$moodlecourse), '*', MUST_EXIST);
                $instance = $DB->get_record('enrol', array('courseid'=>$enrolledcourse->id, 'enrol'=>'manual'), '*', MUST_EXIST);
                $finalcourseid = $enrolledcourse->id;
            } else {
                // enroll into course
                $instance = $DB->get_record('enrol', array('courseid'=>$moodlecourse, 'enrol'=>'manual'), '*', MUST_EXIST);
                $finalcourseid = $moodlecourse;
            }
            // Trigger login event.
            $event = \core\event\user_loggedin::create(
                array(
                    'userid' => $user->id,
                    'objectid' => $user->id,
                    'other' => array('username' => $user->username,'courseid'=>$moodlecourse),
                )
            );
            $event->trigger();

            if (!$enrol_manual = enrol_get_plugin('manual')) {
                throw new coding_exception('Can not instantiate enrol_manual');
            }
            // option to enrol a user as suspended (till he pays)  hanna 13/8/13
            $roletime = 0;  // for teachers that want to see the course before registering  hanna 28/7/14
            if (!empty($user->temporary)){
                $roletime = time() + 60*60* ($user->temporary); //  $roletime = time() + 60*60*24;  // 24 hours hanna 19/10/14
            }

            if (!empty($user->role)){
// enabled by nadavkav 5/8/2014
                //        $enrol_manual->enrol_user($instance, $user->id, $user->role /* 5 == got role from form */ , time(), 0, (empty($user->suspend))? 0 : $user->suspend /* 1 = user is suspended */);
                $enrol_manual->enrol_user($instance, $user->id, $user->role /* 5 == got role from form */ , time(), $roletime, (empty($user->suspend))? 0 : $user->suspend /* 1 = user is suspended */);
            } else {
// enabled by nadavkav 5/8/2014
                //       $enrol_manual->enrol_user($instance, $user->id, 5 /* 5 == role student, for now */ , time(), 0, (empty($user->suspend))? 0 : $user->suspend /* 1 = user is suspended */);
                $enrol_manual->enrol_user($instance, $user->id, 5 /* 5 == role student, for now */ , time(), $roletime, (empty($user->suspend))? 0 : $user->suspend /* 1 = user is suspended */);
            }

            if (!empty($user->groupname)){  // if group is empty then no groups   hanna 24/12/13
                // insert into group
                //    if (!$group = $DB->get_record('groups', array('courseid'=>$moodlecourse, 'name' => $user->groupname))) {
                if (!$group = $DB->get_record('groups', array('courseid'=>$finalcourseid, 'name' => $user->groupname))) {
                    $newgroup = new stdClass();
                    //                       $newgroup->courseid = $moodlecourse;
                    $newgroup->courseid = $finalcourseid;
                    $newgroup->name     = $user->groupname; //str_replace('"','',str_replace("'",'',$user->groupname));
                    $group->id = groups_create_group($newgroup);
                }
                $ok = groups_add_member($group->id, $user->id);
            }  // end !empty($user->groupname)
        }  // end foreach  maybe move the add_to_log into the loop?

        /// todo: make it send our own welcome email to new kidz
        //       if (! $this->send_confirmation_email($user)) {  // welcome email is sent anyway  hanna 12/5/13
        //           print_error('noemail', 'auth_drupal');
        //       }
        // login user into Moodle and redirect to my page
        $USER = authenticate_user_login($user->username, $plainslashedpassword);
        //update_login_count();

        //require_once($CFG->dirroot.'/local/wsdavidson/client/curl.php');
        $serverurl = "http://davidson-old.weizmann.ac.il/moodle/response";
        $params = array('sid'=>$user->sid, 'order_id' => $user->order_id, 'password' => '1co5mood9respon7', 'status' => '1');
        $curl = new curl;
        $resp = $curl->get($serverurl, $params);

        $ws_response = $curl->get_info();
        $ok = file_put_contents('log_'.date("j.n.Y").'.txt', $ws_response['http_code'].' = '.$ws_response['url'] , FILE_APPEND);

        /// TODO: add event
        // print_object($user);  die;
        //set_moodle_cookie($user->username);
        if (count($user->moodlecourseid) > 1){
            redirect($CFG->wwwroot.'/my');
        }
        else {
            redirect($CFG->wwwroot . '/course/view.php?id=' . $finalcourseid);
        }
     }

    /**
     * Send email to specified user with confirmation text and activation link.
     * @param user $user A {@link $USER} object
     * @return bool Returns true if mail was sent OK and false if there was an error.
     */
    function send_confirm_email($user) {  // copy corrections from original on lib/moodlelib line 6080
        global $CFG;
//        $site = get_site();
//        $supportuser = generate_email_supportuser();
//        $site  =  get_string('oursitename','core_davidson');
     //   $supportuser = core_user::get_support_user();
        $supportuser = get_admin();
        echo $supportuser->email ; die;
        // sitename in current lang   hanna 11/7/12
        // $subject = get_string('emailconfirmationsubject', '', format_string($site->fullname));
        $subject = get_string('emailconfirmationsubject', '', get_string('oursitename','core_davidson',null,false,$user->lang),false,$user->lang);
       $username = $user->username;
        $message     = get_string('emailconfirmation', 'auth_drupal', null,false,$user->lang) . '<br/>' ;
 //       $pmessage =  get_string('yourpassword', 'auth_drupal', null,false,$user->lang) . $user->url . '<br/>';
        $forgotpss = get_string('forgotpass', 'auth_drupal', null,false,$user->lang) . 'https://pegasus1.weizmann.ac.il/moodle2/login/forgot_password.php' . '<br/>';
        $umessage = get_string('yourusername', 'auth_drupal', null,false,$user->lang). $username . '<br/>';
        $smessage = get_string('siteaddrss', 'auth_drupal', null,false,$user->lang) . ' ' . 'http://pegasus1.weizmann.ac.il/moodle2/';
        $fullmessage = $message . '<br/>'. $umessage . '<br/>' .  $forgotpss . '<br/>' . $smessage ;
        $messagehtml = text_to_html(get_string('emailconfirmation', 'auth_drupal', null,false,$user->lang ), false, false, true);
        $fullmessagehtml = $messagehtml . '<br/>'. $umessage . '<br/>' . $forgotpss . $smessage ;
        $user->mailformat = 1;  // Always send HTML version as well

   //    email_to_user($supportuser, $supportuser, $subject, $fullmessage . '<br/>' , $fullmessagehtml . '<br/>' );
        return email_to_user($user, $supportuser, $subject, $fullmessage, $fullmessagehtml);
        // return true;
    }

    /**
     * Confirm the new user as registered. This should normally not be used,
     * but it may be necessary if the user auth_method is changed to drupal
     * before the user is confirmed.
     *
     * @param string $username
     * @param string $confirmsecret
     */
    function user_confirm($username, $confirmsecret = null) {
        global $DB;

        $user = get_complete_user_data('username', $username);

        if (!empty($user)) {
            if ($user->confirmed) {
                return AUTH_CONFIRM_ALREADY;
            } else {
                $DB->set_field("user", "confirmed", 1, array("id"=>$user->id));
                if ($user->firstaccess == 0) {
                    $DB->set_field("user", "firstaccess", time(), array("id"=>$user->id));
                }
                return AUTH_CONFIRM_OK;
            }
        } else  {
            return AUTH_CONFIRM_ERROR;
        }
    }

}
