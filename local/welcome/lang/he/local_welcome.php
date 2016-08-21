<?php

/**
 * This plugin sends users a welcome message after logging in
 * and notify a moderator a new user has been added
 * it has a settings page that allow you to configure the messages
 * send.
 *
 * @package    local
 * @subpackage welcome
 * @copyright  2012 Bas Brands
 * @copyright  2012 Bright Alley Knowledge and learning
 * @author     Bas Brands bmbrands@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Moodle welcome';
$string['message_user_enabled'] = 'Enable user messages';
$string['message_user_enabled_desc'] = 'This tickbox enables the sending of welcome messages to new users';
$string['message_user_subject'] = 'שלום [[fullname]] ברוכים הבאים למכון דוידסון';
$string['message_user_subject_desc'] = 'This will be the subject of the email send to the user. Use [[fullname]] as a tag, this will be replace with the users Firstname Lastname.';
$string['message_user'] = 'User message';
$string['message_user_desc'] = 'Message send to new users';

$string['message_moderator_enabled'] = 'Enable moderator messages';
$string['message_moderator_enabled_desc'] = 'This tickbox enables the sending of notification messages to moderators';
$string['message_moderator'] = 'Moderator message';
$string['message_moderator_subject'] = 'Moderator subject';
$string['message_moderator_subject_desc'] = 'This will be the subject of the email send to the moderator. Use [[fullname]] as a tag, this wil be replace with the users Firstname Lastname.';
$string['message_moderator_desc'] = 'Message send to moderators';
$string['moderator_email'] = 'Moderator email';
$string['moderator_email_desc'] = 'New user notifications are send to this email address';

$string['sender_email'] = 'Sender email address';
$string['sender_email_desc'] = 'When new users log in this email address is used to send a notification message, users will be able to see this email address';

$string['sender_firstname'] = 'Welcome message sender firstname';
$string['sender_firstname_desc'] = 'First name used when sending mail to users.';

$string['sender_lastname'] = 'Moderator lastname';
$string['sender_lastname_desc'] = 'Last name used when sending mail to users.';

$string['siteaddrss'] = '
כתובת האתר ללמידה מרחוק היא: ';
$string['default_user_email_subject'] = 'שלום [[fullname]] ברוכים הבאים ל{$a}';
$string['default_user_email'] = 'שלום [[fullname]]

    תודה על שפתחת חשבון ב{$a}

   ***   נא לשמור מייל זה   ***
   
    שם המשתמש שלך הוא [[username]]
    סיסמתך הראשונית היא [[url]]

    ';
/*
$string['default_user_email'] = 'שלום [[fullname]],      <br/>

    תודה על שפתחת חשבון ב{$a}<br/>
    שם המשתמש שלך הוא [[username]]  <br/>
    סיסמתך הראשונית היא [[url]]';
*/
$string['default_moderator_email_subject'] = 'A new user signed up on {$a} : [[fullname]]';
$string['default_moderator_email'] = 'Hi moderator,

	A new user: [[fullname]], has signed up for {$a}';
$string['oursitename']='מכון דוידסון';

$string['usebrowser'] = 'ניתן להתחבר דרך דפדפן כרום או מוזילה פיירפוקס';
$string['regards'] = 'בברכה';
$string['registrationunit'] = 'יחידת הרישום, מכון דוידסון לחינוך מדעי';
