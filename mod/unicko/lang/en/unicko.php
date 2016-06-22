<?php
/**
 * English strings for unicko
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Unicko - Online Meeting';
$string['modulenameplural'] = 'Unicko - Online Meetings';
$string['modulename_help'] = 'Unicko lets you create from within Moodle links to real-time online classrooms.

You can specify the name, description, and calendar entry (which gives a date range for joining the session) of a room.';

$string['unicko'] = 'Unicko';
$string['pluginadministration'] = 'Unicko administration';
$string['pluginname'] = 'Unicko';

$string['unicko:addinstance'] = 'Add a new room';
$string['unicko:host'] = 'Host a meeting';
$string['unicko:manage'] = 'Manage a meeting';
$string['unicko:join'] = 'Join a meeting';
$string['unicko:viewrecording'] = 'View a recording';
$string['unicko:createprivateroom'] = 'Create a private room';
$string['unicko:enrolprivateroom'] = 'Enrol users to a private room';
$string['unicko:joinprivateroom'] = 'Join a meeting in a private room';
$string['unicko:leaveprivateroom'] = 'Leave a private room';
$string['unicko:accessallprivaterooms'] = 'Access all private rooms';

$string['serverurl'] = 'Unicko Server URL';
$string['configserverurl'] = 'The URL of the unicko server.';
$string['host'] = 'Virtual host';
$string['confighost'] = 'Virtual host name on the unicko server.';
$string['secret'] = 'Security Salt';
$string['configsecret'] = 'The security salt of the virutal host on the unicko server.';
$string['privateroommessage'] = 'Private Room Message';
$string['configprivateroommessage'] = 'A message displayed before a user enters a private room.';

$string['nounickos'] = 'There are no virtual classrooms.';
$string['roomtime'] = 'Next room time';
$string['repeattimes'] = 'Repeat sessions';
$string['donotuseroomtime'] ='Don\'t publish any room times';
$string['repeatnone'] = 'No repeats - publish the specified time only';
$string['repeatdaily'] = 'At the same time every day';
$string['repeatweekly'] = 'At the same time every week';
$string['lang'] = 'Language';
$string['english'] = 'English';
$string['hebrew'] = 'Hebrew';
$string['arabic'] = 'Arabic';
$string['allhosts'] = 'Make all participants hosts in a meeting';
$string['join'] = 'Join';
$string['createroom'] = 'Create a room';
$string['managerooms'] = 'Manage rooms';
$string['deleteroom'] = 'Delete room';
$string['roomname'] = 'Room name';
$string['studentsmatching'] = 'Students matching';
$string['students'] = 'Students';
$string['membersmatching'] = 'Members matching';
$string['members'] = 'Members';
$string['roommembers'] = 'Room members';
$string['coursestudents'] = 'Course Students';
$string['backtorooms'] = 'Back to rooms';
$string['backtocourse'] = 'Back to course';
$string['rooms'] = 'Rooms';
$string['name'] = 'Name';
$string['owner'] = 'Owner';
$string['noroomsfound'] = 'No rooms where found';
$string['enrolusers'] = 'Enrol users';
$string['remove'] = 'Remove';
$string['toomanyrooms'] = 'You have too many rooms';
$string['leaveprivateroomcheck'] = 'Are you absolutely sure you want to leave {$a}?';
$string['reports'] = 'Reports';
$string['recordings'] = 'Recordings';

$string['messageprovider:enrolment'] = 'You have been added to room';
$string['emailenrolmentbody'] = 'Dear {$a->username},

You have been added to room
\'{$a->roomname}\'
in course \'{$a->coursename}\'.

You can join the room at {$a->roomurl}.';
$string['emailenrolmentsmall'] = 'You have been added to the room \'{$a->roomname}\'';
$string['emailenrolmentsubject'] = 'Room enrolment: {$a->roomname}';
