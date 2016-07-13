<?php

require('../config.php');

global $USER, $DB;

// Try to prevent searching for sites that allow sign-up.
if (!isset($CFG->additionalhtmlhead)) {
    $CFG->additionalhtmlhead = '';
}
$CFG->additionalhtmlhead .= '<meta name="robots" content="noindex" />';

$userid = optional_param('userid', 0, PARAM_INT);

$courseid = optional_param('courseid', 0, PARAM_INT);
$context = context_course::instance($courseid);

require_login();

$roles = get_user_roles($context, $USER->id, false);
$permit = 0;
foreach ($roles as $role) {
    if ( $role->roleid == '10'){
    $permit = 1;
    }
}
// add premission to reset password for groupteacherview   hanna 25/11/15
if ( !($USER->id == 2062 /* user: jaber bisharat */ or $USER->id == 6162 /* user: natanel.otmi */ or $USER->id == 7 or $USER->id == 3
        or $permit == 1) ) {
    die;
}

$user = $DB->get_record('user',array('id'=>$userid));
$hashedpassword  = md5($user->url.$CFG->passwordsaltmain);
$DB->set_field('user', 'password',  $hashedpassword, array('id'=>$userid));
echo "Password was reset successfully.";
die;
