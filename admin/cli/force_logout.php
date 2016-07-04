<?php
/**
 * Created by PhpStorm.  * User: nthanna
 * Date: 2/10/2015  Time: 1:06 PM
 */
define('CLI_SCRIPT', true);

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/clilib.php');      // cli only functions
global $CFG;

foreach($DB->get_records('sessions', array( 'state' => 0)) as $cursess) {
    if ($cursess->userid > 4 ) {
        echo '</br>' . "user : " . $cursess->userid . " " . userdate($cursess->timecreated) . '<br/>';
        if ( (time() - $cursess->timecreated) / (60 * 60 ) > 14){
            echo 'time:  ' .  (time() - $cursess->timecreated) / ((60 * 60 ) ) . '</br>';
            //   require_logout($curruser);
            $DB->delete_records('sessions', array('userid'=>$cursess->userid));
        }
    }
}