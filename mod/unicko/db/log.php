<?php
/**
 * Definition of log events
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $DB;

$logs = array(
    array('module'=>'unicko', 'action'=>'add', 'mtable'=>'unicko', 'field'=>'name'),
    array('module'=>'unicko', 'action'=>'update', 'mtable'=>'unicko', 'field'=>'name'),
    array('module'=>'unicko', 'action'=>'view', 'mtable'=>'unicko', 'field'=>'name'),
    array('module'=>'unicko', 'action'=>'view all', 'mtable'=>'unicko', 'field'=>'name'),
    array('module'=>'unicko', 'action'=>'addprivate', 'mtable'=>'unicko', 'field'=>'name'),
    array('module'=>'unicko', 'action'=>'enrol', 'mtable'=>'unicko', 'field'=>'name'),
    array('module'=>'unicko', 'action'=>'unenrol', 'mtable'=>'unicko', 'field'=>'name')
);
