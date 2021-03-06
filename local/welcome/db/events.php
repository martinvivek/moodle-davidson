<?php
// This file is part of the Local welcome plugin
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
 * This plugin sends users a welcome message after logging in
 * and notify a moderator a new user has been added
 * it has a settings page that allow you to configure the messages
 * send.
 *
 * @package    local
 * @subpackage welcome
 * @copyright  2015 Bas Brands, basbrands.nl, bas@sonsbeekmedia.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$observers = array(
    /* // Old M29 API v1
    'user_created' => array (
        'handlerfile'     => '/local/welcome/event_handlers.php',
        'handlerfunction' => 'send_welcome',
        'schedule'        => 'instant',
    ),
    */
    // New M31 API v2
    array(
        'eventname' => '\core\event\user_created',
        // Lets use a class
        'callback' => '\local_welcome\observer::send_welcome',

        // Lets use a function (library)
        //'includefile'     => '/local/welcome/event_handlers.php',
        //'callback' => 'send_welcome',
        'schedule'        => 'instant',
        //'internal' => false
    ),
);
