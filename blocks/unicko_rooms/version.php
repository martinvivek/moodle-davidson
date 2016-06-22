<?php
/**
 * Version details
 *
 * @package    block_unicko
 * @copyright  2015 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2015063000;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires  = 2015051100;        // Requires this Moodle version
$plugin->component = 'block_unicko_rooms'; // Full name of the plugin (used for diagnostics)
$plugin->cron = 300;
$plugin->dependencies = array(
    'mod_unicko' => ANY_VERSION
);
