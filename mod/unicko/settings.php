<?php
/**
 * Settings for unicko  
 * 
 * @package   mod_bigbluebuttonbn
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$settings->add( new admin_setting_configtext( 'unicko/server_url', get_string( 'serverurl', 'unicko' ), get_string( 'configserverurl', 'unicko' ), 'http://moodle.unicko.dev' ) );
$settings->add( new admin_setting_configtext( 'unicko/host', get_string( 'host', 'unicko' ), get_string( 'confighost', 'unicko' ), 'moodle.dev' ) );
$settings->add( new admin_setting_configtext( 'unicko/secret', get_string( 'secret', 'unicko' ), get_string( 'configsecret', 'unicko' ), 'IG5GEWiBKU+vATCjOKW4A88AEQU=' ) );
$settings->add( new admin_setting_configtext( 'unicko/privateroommessage', get_string( 'privateroommessage', 'unicko' ), get_string( 'configprivateroommessage', 'unicko' ), '' ) );
