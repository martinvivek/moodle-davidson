<?php
/**
 * Add event handlers for unicko
 *
 * @package    mod_unicko
 * @category   event
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

defined('MOODLE_INTERNAL') || die();

$handlers = array(
    'user_deleted' => array (
        'handlerfile'      => '/mod/unicko/locallib.php',
        'handlerfunction'  => 'unicko_user_deleted',
        'schedule'         => 'instant', // 'cron'
        'internal'         => 1,
    ),
);
