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

function send_welcome($user) {
    global $CFG, $SITE;

    require_once($CFG->dirroot . '/local/welcome/locallib.php');

    $sender = get_admin();

    if (!empty($user->email)) {

        $config = get_config('local_welcome');

        $moderator = clone($sender);

        if (!empty($config->auth_plugins)) {
            $auths = explode(',', $config->auth_plugins);
            if (!in_array($user->auth, $auths)) {
                return '';
            }
        } else {
            return '';
        }

        $peg = 'http://pegasus1.weizmann.ac.il/moodle2/ ';  // site in user's lang  hanna 29/2/16
        if ($user->lang == 'en' or $user->lang == 2){
            $peg = 'http://pegasus1.weizmann.ac.il/moodle2/?lang=en';
        }
        elseif ( $user->lang == 'ar' ) {
            $peg = 'http://pegasus1.weizmann.ac.il/moodle2/?lang=ar';
        }

        if (!empty($user->country)) {
            $user->country = get_string($user->country, 'countries');
        } else {
            $user->country = 'IL';
        }

        $moderator->email = $config->moderator_email;

        $sender->email = $config->sender_email;
        $sender->firstname = $config->sender_firstname;
        $sender->lastname = $config->sender_lastname;

        $message_user_enabled = $config->message_user_enabled;
//        $message_user = $config->message_user;     // hanna 29/6/15
        $message_user = get_string('default_user_email','local_welcome',get_string('oursitename','local_welcome',null,false,$user->lang),false,$user->lang); // hanna 8/6/14
//        $message_user_subject = $config->message_user_subject;
        $message_user_subject = get_string('message_user_subject','local_welcome', null, false, $user->lang);

        $message_moderator_enabled = $config->message_moderator_enabled;
        $message_moderator = $config->message_moderator;
        $message_moderator_subject = $config->message_moderator_subject;

        $welcome = new local_welcome();

        $message_user = $welcome->replace_values($user, $message_user);
        $message_user_subject = $welcome->replace_values($user, $message_user_subject);
        $message_moderator = $welcome->replace_values($user, $message_moderator);
        $message_moderator_subject = $welcome->replace_values($user, $message_moderator_subject);

        $addr = "<br/>" . get_string('siteaddrss', 'local_welcome', null, false, $user->lang) . "<br/>" . $peg . "<br/>";
        $browseinfo = "<br/>" . get_string('usebrowser', 'local_welcome', null, false, $user->lang) . "<br/>" ;
        $signing = "<br/>" . get_string('regards','local_welcome', null, false, $user->lang) . "<br/>" . get_string('registrationunit','local_welcome', null, false, $user->lang);
        $message_user .= $addr . $browseinfo . $signing;

        if ($user->lang == 'he' || $user->lang == 'ar') {
            $message_user = '<div style="direction: rtl;text-align: right;">' . $message_user . ' </div>';
        }


        if (!empty($message_user) && !empty($sender->email) && $message_user_enabled) {
            email_to_user($user, $sender, $message_user_subject, html_to_text($message_user), $message_user);
        }

        $message_moderator .= ' ' . '<br/>' . ' ' . $message_user ;  //  hanna 19/8/14

        if (!empty($message_moderator) && !empty($sender->email) && $message_moderator_enabled) {
            email_to_user($moderator, $sender, $message_moderator_subject, html_to_text($message_moderator), $message_moderator);
        }
    }
}