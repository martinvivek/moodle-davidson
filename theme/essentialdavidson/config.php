<?php
// This file is part of Moodle - http://moodle.org/
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
 * essentialdavidson is a basic child theme of Essential to help you as a theme
 * developer create your own child theme of Essential.
 *
 * @package     theme_essentialdavidson
 * @copyright   2015 Gareth J Barnard
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$THEME->name = 'essentialdavidson';

$THEME->yuicssmodules = array();
$THEME->parents = array('essential');

// Click user picture to get a quick action user's menu (teachers and admins only)
$THEME->sheets[] = 'renderer_usermenu';
$THEME->javascripts_footer[] = 'usermenu';

//$THEME->sheets[] = 'essentialdavidson';
$THEME->sheets[] = 'radial-progress';
$THEME->sheets[] = 'davidson-extra';

/* If you need all of the Essential settings values, then comment this ($THEME->parents_exclude_sheets)
   out and look at 'theme_essentialdavidson_process_css' in lib.php for more information. */
//$THEME->parents_exclude_sheets = array(
//    'essential' => array(
//        'essential-settings',
//        'custom'
//    )
//);

$THEME->supportscssoptimisation = false;

/* Other layouts will use the Essential ones, so it is important that the header.php file keeps things the same,
   like 'essentialnavbar'.
   If you are only looking to change the styles by adding your own to 'essentialdavidson.css' in the styles folder, then
   you can remove this ($THEME->layouts). */
$THEME->layouts = array(
    /*'course' => array(
        'file' => 'columns3.php',
        'regions' => array('side-pre', 'side-post', 'footer-left', 'footer-middle', 'footer-right'),
        'defaultregion' => 'side-post',
    ),*/
    // Main course page.
    'course' => array(
        'file' => 'columns3-course.php',
        'regions' => array('above-page', 'side-pre', 'side-post', 'footer-left', 'footer-middle', 'footer-right'),
        'defaultregion' => 'side-post',
    ),
    // part of course, typical for modules - default page layout if $cm specified in require_login().
    'incourse' => array(
        'file' => 'columns3-davidson.php',
        'regions' => array('side-pre', 'side-post', 'footer-left', 'footer-middle', 'footer-right'),
        'defaultregion' => 'side-pre',
    ),
    'incourse_reports' => array(
        'file' => 'columns2-davidson.php',
        'regions' => array('side-pre', 'side-post', 'footer-left', 'footer-middle', 'footer-right'),
        'defaultregion' => 'side-pre',
    ),
);

$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->csspostprocess = 'theme_essentialdavidson_process_css';
