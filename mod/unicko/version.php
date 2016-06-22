<?php
/**
 * Defines the version of unicko
 *
 * This code fragment is called by moodle_needs_upgrading() and
 * /admin/index.php
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'mod_unicko'; // To check on upgrade, that module sits in correct place
$plugin->version   = 2016052400;   // The current module version (Date: YYYYMMDDXX)
$plugin->requires  = 2013051400;   // Requires this Moodle version
$plugin->cron      = 0;            // Period for cron to check this module (secs)
