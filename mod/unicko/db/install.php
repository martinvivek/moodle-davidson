<?php
/**
 * This file replaces the legacy STATEMENTS section in db/install.xml,
 * lib.php/modulename_install() post installation hook and partially defaults.php
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Post installation procedure
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_unicko_install() {
}

/**
 * Post installation recovery procedure
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_unicko_install_recovery() {
}
