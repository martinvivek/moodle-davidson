<?php
// This client for local_wstemplate is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//

/**
 * XMLRPC client for Moodle 2 - local_wstemplate
 *
 * This script does not depend of any Moodle code,
 * and it can be called from a browser.
 *
 * @authorr Jerome Mouneyrac
 */

/// MOODLE ADMINISTRATION SETUP STEPS
// 1- Install the plugin
// 2- Enable web service advance feature (Admin > Advanced features)
// 3- Enable XMLRPC protocol (Admin > Plugins > Web services > Manage protocols)
// 4- Create a token for a specific user and for the service 'My service' (Admin > Plugins > Web services > Manage tokens)
// 5- Run this script directly from your browser: you should see 'Hello, FIRSTNAME'

echo "Calling web service {$_GET['action']}...\n";

/// SETUP - NEED TO BE CHANGED
$token = 'c39fd914190710e567f421c232cc3965';
$domainname = 'http://pegasus1.weizmann.ac.il/moodle2';

if ($_GET['action']=='unenroll') {
    $functionname = 'local_wsdavidson_unenroll_user_from_course';
    //$params = array('useridnumber' => '025261793', 'courseidnumber'=>'222');
    $params = array('useridnumber' => '316667187', 'courseidnumber'=>'222');

}

if ($_GET['action']=='unsuspend') {
    $functionname = 'local_wsdavidson_unsuspend_user_from_course';
    //$params = array('useridnumber' => '025261793', 'courseidnumber'=>'222');
    //$params = array('useridnumber' => '1234576', 'courseidnumber'=>'222');
    $params = array('useridnumber' => '310038112', 'courseidnumber'=>'9536');

}

///// XML-RPC CALL
header('Content-Type: text/plain');
$restformat = '&moodlewsrestformat=json';
$serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname.$restformat;

require_once('./curl.php');
$curl = new curl;
$resp = $curl->post($serverurl, $params);
print_r($resp);
//$decoded_jason = json_decode($resp);
//print_r($decoded_jason);
//echo json_decode($resp);