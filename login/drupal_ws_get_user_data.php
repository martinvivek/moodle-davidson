<?php

// todo: Warning !!! - add security and param type and content validation.
$sid = $_GET['sid'];
if (!is_numeric($sid)) {
    echo "Error $sid should be numeric!";
    die;
}
$order_id = $_GET['order_id'];
if (!is_numeric($order_id)) {
    echo "Error $order_id should be numeric!";
    die;
}
// Basic security
if (!(MD5($sid . "Vfr45tgbnhy67ujm") == $_GET['key'])) {
    echo "Error - you should use a proper key!";
    //die;
}

// todo: Warning !!! - do not commit to public git repository
// Following sensitive credentials should probably
// be outside of this file, and included into it.
define('DAVIDSON_DOMAIN', "davidson-db.weizmann.ac.il");
define('DAVIDSON_DBUSER', "moodleuser");
define('DAVIDSON_DBPASS', "xsw23edc");
define('DAVIDSON_DB', "davidson_db");

$mysqli = new mysqli(DAVIDSON_DOMAIN, DAVIDSON_DBUSER, DAVIDSON_DBPASS, DAVIDSON_DB);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
/* change character set to utf8 */
if (!$mysqli->set_charset("utf8")) {
    //printf("Error loading character set utf8: %s\n", $mysqli->error);
} else {
    //printf("Current character set: %s\n", $mysqli->character_set_name());
}

$res = $mysqli->query("SELECT nid FROM webform_submitted_data WHERE sid = ".$sid." LIMIT 0, 1");
$res->data_seek(1);
$row = $res->fetch_assoc();
$nid = $row['nid'];

$res = $mysqli->query("SELECT submitted FROM  webform_submissions WHERE sid = ".$sid." LIMIT 0, 1");
$res->data_seek(1);
$row = $res->fetch_assoc();
$submitdatetime = $row['submitted'];

$res = $mysqli->query("
SELECT * FROM webform_submitted_data AS wsd
LEFT JOIN webform_component AS wc ON wsd.cid = wc.cid AND wsd.nid = wc.nid
WHERE sid = '".$sid."' AND wsd.nid = '".$nid."'");

$newuser = new stdClass();
$newuser->sid = $sid;
$newuser->order_id = $order_id;

// Date/Time of submitted form request on davidson's site
// We use it to prevent reoccurring submission of multiple users (security)
$newuser->submitdatetime = $submitdatetime;

for ($row_no = 0; $row_no < $res->num_rows; $row_no++) {
    $res->data_seek($row_no);
    $row = $res->fetch_assoc();

    if ($row['form_key'] == 'suspend') $newuser->suspend = $row['data'];
    if ($row['form_key'] == 'email') $newuser->email = $row['data'];
    if ($row['form_key'] == 'school_name') $newuser->institution = str_replace('"','',str_replace("'",'',$row['data']));
    if ($row['form_key'] == 'school_name') $newuser->school_name = str_replace('"','',str_replace("'",'',$row['data']));
    if ($row['form_key'] == 'tz') $newuser->idnumber = $row['data'];
    if ($row['form_key'] == 'gender') $newuser->gender = ($row['data'] == '0')?1:2;
    if ($row['form_key'] == 'lang') $newuser->lang = $row['data'];
    if ($row['form_key'] == 'fname') $newuser->firstname = $row['data'];
    if ($row['form_key'] == 'lname') $newuser->lastname = $row['data'];
    if ($row['form_key'] == 'chat') $newuser->messagesdisabled = $row['data'];
//    if (!empty($newuser->lang) && $newuser->lang != 2) {
//        if ($row['form_key'] == 'city') $newuser->city = get_city($row['data']);
//    } else {
    if ($row['form_key'] == 'city') $newuser->city = $row['data'];
    if ($row['form_key'] == 'city_en') $newuser->city = $row['data'];

//    }
    if ($row['form_key'] == 'country') $newuser->country = $row['data'];
    if ($row['form_key'] == 'country') $newuser->country = $row['data'];
    if ($row['form_key'] == 'phone') $newuser->phone1 = $row['data'];
    if ($row['form_key'] == 'cell_phone') $newuser->phone2 = $row['data'];
    if ($row['form_key'] == 'mobile') $newuser->phone2 = $row['data'];
    if ($row['form_key'] == 'address') $newuser->address = $row['data'];
    if ($row['form_key'] == 'class') $newuser->department = $row['data'];
    if ($row['form_key'] == 'class') $newuser->class = $row['data'];
    if ($row['form_key'] == 'zip') $newuser->zip = $row['data'];
    if ($row['form_key'] == 'group') $newuser->group = $row['data'];
    if ($row['form_key'] == 'manualgroup') $newuser->manualgroup = $row['data'];
    if ($row['form_key'] == 'subject') $newuser->subject = $row['data'];
    if ($row['form_key'] == 'role') $newuser->role = $row['data'];
    if ($row['form_key'] == 'icq') $newuser->icq = $row['data'];
    if ($row['form_key'] == 'skype') $newuser->skype = $row['data'];
    if ($row['form_key'] == 'aim') $newuser->aim = $row['data'];
    if ($row['form_key'] == 'birthday') $newuser->birthday = $row['data'];
    if ($row['form_key'] == 'temporary') $newuser->temporary = $row['data'];
    if ($row['form_key'] == 'address') $liron_street = $row['data'];
    if ($row['form_key'] == 'house_number') $newuser->house_number = $row['data'];


//    if (!empty($newuser->lang) && $newuser->lang != 2) {
//        if ($row['form_key'] == 'school_town') $newuser->school_town = get_city($row['data']);
//    } else {
    if ($row['form_key'] == 'school_town') $newuser->school_town = $row['data'];
//    }

    if ($row['form_key'] == 'level') $newuser->level = $row['data'];
    if ($row['form_key'] == 'code_course') $newuser->code_course = $row['data'];
    if ($row['form_key'] == 'courseidnumbers') $newuser->course_idnumbers = $row['data'];

}

print_r($newuser);die;

if (empty ($newuser->house_number)) {$newuser->house_number = '0';}
if (empty ($newuser->zip)) {$newuser->zip = '11111';}
if (empty ($liron_street)) {$liron_street = 'none';}


/*  add street, number, zip  */
$newuser->street = str_replace(',', ' ', $liron_street);
$newuser->address = $newuser->street . ',' . $newuser->house_number . ',' . $newuser->zip;

switch ($newuser->lang) {
    case 0:
        $mlang = 'he';
        $country = 'IL';
        if (!empty($newuser->school_town)) {
            $newuser->school_town = get_city($newuser->school_town);
        }
        if (!empty($newuser->city)) {
            $newuser->city = get_city($newuser->city);
        }
        break;
    case 1:
        $mlang = 'ar';
        $country = 'IL';
        if (!empty($newuser->school_town)) {
            $newuser->school_town = get_city($newuser->school_town);
        }
        if (!empty($newuser->city)) {
            $newuser->city = get_city($newuser->city);
        }
        break;
    case 2:
        $mlang = '2';
        $country = '';
        break;
}
$newuser->lang = $mlang;
$newuser->country = $country;

echo json_encode($newuser);

//////////////// end of file.

function get_city($citycode) {

    if ($citycode == '') { return '';}
    else {
        $mysqli = new mysqli(DAVIDSON_DOMAIN, DAVIDSON_DBUSER, DAVIDSON_DBPASS, DAVIDSON_DB);
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        /* change character set to utf8 */
        if (!$mysqli->set_charset("utf8")) {
            //printf("Error loading character set utf8: %s\n", $mysqli->error);
        } else {
            //printf("Current character set: %s\n", $mysqli->character_set_name());
        }

        $res = $mysqli->query("SELECT title FROM node WHERE nid = ".$citycode." LIMIT 0, 1");
        $res->data_seek(1);
        $row = $res->fetch_assoc();
        return $row['title'];
    }  // end  $citycode == ''
}
