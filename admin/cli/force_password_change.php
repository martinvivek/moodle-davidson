<?php
/**
 * Created by PhpStorm.
 * User: nthanna
 * Date: 1/22/2015   Time: 2:41 PM
 */
define('CLI_SCRIPT', true);

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/clilib.php');      // cli only functions

foreach($DB->get_records('user', array( 'deleted' => 0)) as $curruser) {
    if ($curruser->id > 20) {
        if ((time() - $curruser->lastaccess) / (60 * 60 * 24) > 365) {
            set_user_preference('auth_forcepasswordchange', true, $curruser->id);
            //   echo "user : " . $curruser->id . " " . userdate($curruser->lastlogin);
        }
    }
}