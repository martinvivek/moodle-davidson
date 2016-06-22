<?php
/**
 * Core external functions and service definitions.
 *
 * The functions and services defined on this file are
 * processed and registered into the Moodle DB after any
 * install or upgrade operation. All plugins support this.
 *
 * @package    unicko_webservice
 * @category   webservice
 * @copyright  2016 Ofir Riss and Eyal Levy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = array(
);

$services = array(
   'Unicko client web service'  => array(
        'functions' => array (),
        'requiredcapability' => '',
        'enabled' => 0,
        'restrictedusers' => 0,
        'shortname' => 'unickoclientservice',
        'downloadfiles' => 0
    ),
);
