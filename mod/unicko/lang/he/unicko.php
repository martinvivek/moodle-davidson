<?php
/**
 * Hebrew strings for unicko
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'יוניקו - מפגש מקוון';
$string['modulenameplural'] = 'יוניקו - מפגשים מקוונים';
$string['modulename_help'] = 'יוניקו מאפשר ליצור מתוך מודל כיתות וירטואליות.

ניתן לבחור שם, תיאור ותאריך ביומן לחדר.';
$string['roomname'] = 'room name';

$string['unicko'] = 'Unicko';
$string['pluginadministration'] = 'ניהול unicko';
$string['pluginname'] = 'Unicko';

$string['unicko:addinstance'] = 'הוסף חדר';
$string['unicko:host'] = 'אירוח פגישה';
$string['unicko:manage'] = 'ניהול כיתה וירטואלית';
$string['unicko:join'] = 'השתתפות בפגישה';
$string['unicko:viewrecording'] = 'צפייה בהקלטה';
$string['unicko:createprivateroom'] = 'יצירת חדר פרטי';
$string['unicko:enrolprivateroom'] = 'צירוף משתתפים לחדר פרטי';
$string['unicko:joinprivateroom'] = 'כניסה לפגישה בחדר פרטי';
$string['unicko:leaveprivateroom'] = 'הסרה מחדר פרטי';
$string['unicko:accessallprivaterooms'] = 'כניסה לכל החדרים הפרטיים';

$string['serverurl'] = 'Unicko Server URL';
$string['configserverurl'] = 'The URL of the unicko server.';
$string['host'] = 'Virtual host';
$string['confighost'] = 'Virtual host name on the unicko server.';
$string['secret'] = 'Security Salt';
$string['configsecret'] = 'The security salt of the virutal host on the unicko server.';
$string['privateroommessage'] = 'הודעה בחדר פרטי';
$string['configprivateroommessage'] = 'הודעה המוצגת למשתמשים לפני כניסה לחדר פרטי.';

$string['nounickos'] = 'אין כיתות וירטואליות.';
$string['roomtime'] = 'מועד המפגש';
$string['repeattimes'] = 'אירוע חוזר';
$string['donotuseroomtime'] ='ללא פירסום מועד';
$string['repeatnone'] = 'ללא חזרה - פרסם את הזמן המצויין בלבד';
$string['repeatdaily'] = 'באותו זמן כל יום';
$string['repeatweekly'] = 'באותו זמן כל שבוע';
$string['lang'] = 'שפה';
$string['english'] = 'אנגלית';
$string['hebrew'] = 'עברית';
$string['arabic'] = 'ערבית';
$string['allhosts'] = 'תן הרשאות מארח לכל המשתתפים';
$string['join'] = 'כניסה';
$string['createroom'] = 'צור חדר';
$string['managerooms'] = 'נהל חדרים';
$string['deleteroom'] = 'מחק חדר';
$string['roomname'] = 'שם החדר';
$string['studentsmatching'] = 'תלמידים';
$string['students'] = 'תלמידים';
$string['membersmatching'] = 'משתתפים';
$string['members'] = 'משתתפים';
$string['roommembers'] = 'משתתפים בחדר';
$string['coursestudents'] = 'תלמידים בקורס';
$string['backtorooms'] = 'בחזרה לחדרים';
$string['backtocourse'] = 'בחזרה לקורס';
$string['rooms'] = 'חדרים';
$string['name'] = 'שם';
$string['owner'] = 'אחראי';
$string['noroomsfound'] = 'לא נמצאו חדרים';
$string['enrolusers'] = 'הוסף משתתפים';
$string['remove'] = 'הסר';
$string['toomanyrooms'] = 'חרגת ממספר החדרים המותר';
$string['leaveprivateroomcheck'] = 'האם את/ה בטוח/ה שברצונך לעזוב את {$a}?';
$string['reports'] = 'דוחות';
$string['recordings'] = 'הקלטות';

$string['messageprovider:enrolment'] = 'הוסיפו אותך לחדר';
$string['emailenrolmentbody'] = 'שלום {$a->username},

הוסיפו אותך לחדר
\'{$a->roomname}\'
בקורס \'{$a->coursename}\'.

תוכל להכנס לחדר ב {$a->roomurl}.';
$string['emailenrolmentsmall'] = 'הוסיפו אותך לחדר \'{$a->roomname}\'';
$string['emailenrolmentsubject'] = 'הצטרפות לחדר: {$a->roomname}';
