<?php

// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * Hishtalmut certificate type
 *
 * @package    mod
 * @subpackage certificate
 * @copyright  Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from view.php
}

$pdf = new PDF($certificate->orientation, 'pt', 'Letter', true, 'UTF-8', false);

$pdf->SetTitle($certificate->name);
$pdf->SetProtection(array('modify'));
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(false, 0);
$pdf->AddPage();

// Define variables
// Landscape
if ($certificate->orientation == 'L') {
    $x = 28;
    $y = 125;
    $sealx = 590;
    $sealy = 425;
    $sigx = 130;
    $sigy = 440;
    $custx = 133;
    $custy = 440;
    $wmarkx = 100;
    $wmarky = 90;
    $wmarkw = 600;
    $wmarkh = 420;
    $brdrx = 0;
    $brdry = 0;
    $brdrw = 792;
    $brdrh = 612;
    $codey = 505;
} else { // Portrait
    $x = 28;
    $y = 80;
    $sealx = 440;
    $sealy = 590;
    $sigx = 85;
    $sigy = 580;
    $custx = 88;
    $custy = 580;
    $wmarkx = 78;
    $wmarky = 130;
    $wmarkw = 450;
    $wmarkh = 480;
    $brdrx = 10;
    $brdry = 10;
    $brdrw = 594;
    $brdrh = 771;
    $codey = 660;
}

$enddate_result = $DB->get_record_sql("SELECT DATE_FORMAT(FROM_UNIXTIME(cid.data),'%d/%m/%Y') AS enddate
 FROM mdl_coursemetadata_info_data AS cid
 JOIN mdl_coursemetadata_info_field AS cif ON cid.fieldid = cif.id
 WHERE cif.shortname = 'enddate' AND cid.course = {$course->id} ");

$startdate_result = $DB->get_record_sql("SELECT DATE_FORMAT(FROM_UNIXTIME(cid.data),'%d/%m/%Y') AS startdate
 FROM mdl_coursemetadata_info_data AS cid
 JOIN mdl_coursemetadata_info_field AS cif ON cid.fieldid = cif.id
 WHERE cif.shortname = 'startDate' AND cid.course = {$course->id} ");

$schoolyear = $DB->get_record_sql("SELECT cid.data AS studyyear
 FROM mdl_coursemetadata_info_data AS cid
 JOIN mdl_coursemetadata_info_field AS cif ON cid.fieldid = cif.id
 WHERE cif.shortname = 'studyyear' AND cid.course = {$course->id} ");

$gmulim = $DB->get_record_sql("SELECT cid.data AS gmul
 FROM mdl_coursemetadata_info_data AS cid
 JOIN mdl_coursemetadata_info_field AS cif ON cid.fieldid = cif.id
 WHERE cif.shortname = 'gmul' AND cid.course = {$course->id} ");

$countedhours = $DB->get_record_sql("SELECT cid.data AS hourcount
 FROM mdl_coursemetadata_info_data AS cid
 JOIN mdl_coursemetadata_info_field AS cif ON cid.fieldid = cif.id
 WHERE cif.shortname = 'hourcount' AND cid.course = {$course->id} ");

// Add images and lines
certificate_print_image($pdf, $certificate, CERT_IMAGE_BORDER, $brdrx, $brdry, $brdrw, $brdrh);
certificate_draw_frame_letter($pdf, $certificate);
// Set alpha to semi-transparency
$pdf->SetAlpha(0.1);
certificate_print_image($pdf, $certificate, CERT_IMAGE_WATERMARK, $wmarkx, $wmarky, $wmarkw, $wmarkh);
$pdf->SetAlpha(1);
certificate_print_image($pdf, $certificate, CERT_IMAGE_SEAL, $sealx, $sealy, '', '');
certificate_print_image($pdf, $certificate, CERT_IMAGE_SIGNATURE, $sigx, $sigy, '', '');

// Add text
$pdf->SetTextColor(0, 0, 120);
//certificate_print_text($pdf, $x, $y, 'C', 'freesans', '', 30, get_string('beaver_certificate', 'core_davidson'));
$pdf->SetTextColor(0, 0, 0);
//certificate_print_text($pdf, $x, $y + 55, 'C', 'freeserif', '', 20, get_string('certify', 'certificate'));
certificate_print_text($pdf, $x + 28 , $y + 35, 'L', 'freesans', '', 12, certificate_get_date($certificate, $certrecord, $course));
//certificate_print_text($pdf, $x, $y + 175, 'C', 'freesans', '', 20, fullname($USER));
certificate_print_text($pdf, $x + 244  , $y + 168, 'C', 'freesans', '', 11, fullname($USER) . '   ' . get_string('idnumber') . ' ' . $USER->idnumber);

// $usergroup = array_shift(groups_get_all_groups($course->id, $USER->id));
// list($city, $school, $shchva) = explode('_',$usergroup->name);
//$school = str_replace('-', ' ', $school);
//certificate_print_text($pdf, $x, $y + 205, 'C', 'freesans', '', 20, $USER->institution);
//certificate_print_text($pdf, $x - 20, $y + 205, 'C', 'freesans', '', 20, $school);
//certificate_print_text($pdf, $x, $y + 335, 'C', 'freesans', '', 20, get_string('beaver_shchva'.$shchva, 'core_davidson'));
//certificate_print_text($pdf, $x, $y + 155, 'C', 'freesans', '', 20, get_string('statement', 'certificate'));
certificate_print_text($pdf, $x + 175, $y + 217, 'C', 'freesans', '', 11, $course->fullname);
//certificate_print_text($pdf, $x + 10, $y + 242, 'C', 'freesans', '', 12, userdate($course->startdate, get_string('strftimedate', 'langconfig')).' - '.$enddate_result->enddate);
//certificate_print_text($pdf, $x + 10, $y + 242, 'C', 'freesans', '', 12, $enddate_result->enddate . '  -  ' . userdate($course->startdate, get_string('strftimedate', 'langconfig')) );
certificate_print_text($pdf, $x + 10, $y + 242, 'C', 'freesans', '', 11, $enddate_result->enddate . ' - ' . $startdate_result->startdate );

certificate_print_text($pdf, $x + 45, $y + 242, 'L', 'freesans', '', 11, $schoolyear->studyyear);

//certificate_print_text($pdf, $x, $y + 283, 'C', 'freeserif', '', 10, certificate_get_grade($certificate, $course));
certificate_print_text($pdf, $x, $y + 311, 'C', 'freeserif', '', 10, certificate_get_outcome($certificate, $course));
//if ($certificate->printhours) {
   // certificate_print_text($pdf, $x + 25, $y + 316, 'C', 'freeserif', '', 12, get_string('credithours', 'certificate') . ': ' . $certificate->printhours);
   // certificate_print_text($pdf, $x + 85, $y + 316, 'C', 'freesans', '', 11, $certificate->printhours . '  ' . get_string('credithours', 'certificate') );
//}
certificate_print_text($pdf, $x + 85, $y + 316, 'C', 'freesans', '', 11, $countedhours->hourcount . '  ' . get_string('credithours', 'certificate') );
  //  get the hours from course fields instead of certificate fields  23/2/15
/*
certificate_print_text($pdf, $x, $codey, 'C', 'freeserif', '', 10, certificate_get_code($certificate, $certrecord));
$i = 0;
if ($certificate->printteacher) {
    $context = context_module::instance($cm->id);
    if ($teachers = get_users_by_capability($context, 'mod/certificate:printteacher', '', $sort = 'u.lastname ASC', '', '', '', '', false)) {
        foreach ($teachers as $teacher) {
            $i++;
            certificate_print_text($pdf, $sigx, $sigy + ($i * 12), 'L', 'freeserif', '', 12, fullname($teacher));
        }
    }
}
*/
//certificate_print_text($pdf, $custx - 80, $custy + 35, 'C', null, null, null, $certificate->customtext);
certificate_print_text($pdf, $x + 195, $custy + 35, 'L', 'freesans', '', 12, $gmulim->gmul);
//certificate_print_text($pdf, $custx - 80, $custy + 35, 'C', null, null, null, $course->idnumber);
