<?php //$Id: user_bulk_confirm.php,v 1.3.2.1 2007/11/13 09:02:12 skodak Exp $
/**
* script for bulk user disable messages operations
*/

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');

$confirm = optional_param('confirm', 0, PARAM_BOOL);
require_login();

admin_externalpage_setup('userbulk');
require_capability('moodle/user:update', context_system::instance());

$return = $CFG->wwwroot.'/'.$CFG->admin.'/user/user_bulk.php';

if (empty($SESSION->bulk_users)) {
    redirect($return);
}

//admin_externalpage_print_header();
echo $OUTPUT->header();  // hanna 23/10/12

if ($confirm and confirm_sesskey()) {
    $in = implode(',', $SESSION->bulk_users);
    if ($rs = $DB->get_recordset_select('user', "id IN ($in)")) {
        foreach ($rs as $user) {
            set_user_preference('messagesdisabled',1,$user->id);
        }
        $rs->close();
    }
    redirect($return, get_string('changessaved'));

    }
else {
    $in = implode(',', $SESSION->bulk_users);
    $userlist = $DB->get_records_select_menu('user', "id IN ($in)",null,'', 'id,'.$DB->sql_fullname().' AS fullname');
    $usernames = implode(', ', $userlist);

    $OUTPUT->heading(get_string('confirmation', 'admin'));
    //$OUTPUT->confirm($message, $buttoncontinue, $buttoncancel).');
    //notice_yesno(get_string('notabletomsg', '', $usernames), 'user_bulk_disablemsg.php', 'user_bulk.php', $optionsyes, NULL, 'post', 'get');
    //echo $OUTPUT->confirm(get_string('notabletomsg', '',$usernames),new moodle_url('user_bulk_disablemsg.php?confirm=1&sesskey='. sesskey()), new moodle_url('user_bulk.php?confirm=0&sesskey='. sesskey()));   //  hanna 23/10/12
    $formcontinue = new single_button(new moodle_url('user_bulk_enablemsg.php', array('confirm' => 1)), get_string('yes'));
    $formcancel = new single_button(new moodle_url('user_bulk.php'), get_string('no'), 'get');
    echo $OUTPUT->confirm(get_string('confirmcheckfull', '', $usernames), $formcontinue, $formcancel);
}
//admin_externalpage_print_footer();
echo $OUTPUT->footer();   // hanna 23/10/12

