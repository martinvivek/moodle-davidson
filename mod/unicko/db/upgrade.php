<?php
/**
 * This file keeps track of upgrades to the unicko module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute unicko upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_unicko_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2015070100) {
        // Define field course to be added.
        $table = new xmldb_table('unicko');
        $field = new xmldb_field('allhosts', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'lang');

        // Add field allhosts.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

    }

    return true;
}
