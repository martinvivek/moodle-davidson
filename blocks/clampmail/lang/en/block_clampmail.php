<?php
// This file is part of Moodle - http://moodle.org/
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
 * @package   block_clampmail
 * @copyright 2013 Collaborative Liberal Arts Moodle Project
 * @copyright 2012 Louisiana State University (original Quickmail block)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Quickmail';
$string['clampmail:cansend'] = "Allows users to send email through Quickmail";
$string['clampmail:canconfig'] = "Allows users to configure Quickmail instance.";
$string['clampmail:canimpersonate'] = "Allows users to log in as other users and view history.";
$string['clampmail:allowalternate'] = "Allows users to add an alternate email for courses.";
$string['clampmail:addinstance'] = 'Add a new Quickmail block';
$string['alternate'] = 'Alternate Emails';
$string['composenew'] = 'Compose New Email';
$string['email'] = 'Email';
$string['drafts'] = 'View Drafts';
$string['history'] = 'View History';
$string['log'] = $string['history'];
$string['from'] = 'From';
$string['selected'] = 'Selected Recipients';
$string['add_button'] = 'Add';
$string['remove_button'] = 'Remove';
$string['add_all'] = 'Add All';
$string['remove_all'] = 'Remove All';
$string['role_filter'] = 'Role Filter';
$string['no_filter'] = 'No filter';
$string['potential_users'] = 'Potential Recipents';
$string['potential_sections'] = 'Potential Sections';
$string['no_section'] = 'Not in a section';
$string['all_sections'] = 'All Sections';
$string['attachment'] = 'Attachment(s)';
$string['subject'] = 'Subject';
$string['message'] = 'Message';
$string['send_email'] = 'Send Email';
$string['addme'] = 'Add Me';
$string['save_draft'] = 'Save Draft';
$string['actions'] = 'Actions';
$string['signature'] = 'Signatures';
$string['delete_confirm'] = 'Are you sure you want to delete message with the following details: {$a}';
$string['title'] = 'Title';
$string['sig'] ='Signature';
$string['default_flag'] = 'Default';
$string['config'] = 'Configuration';
$string['receipt'] = 'Receive a copy';
$string['receipt_help'] = 'Receive a copy of the email being sent';

$string['parentmail'] = 'send to second email';
$string['parentmail_help'] = 'send a copy of the email to second email';

$string['no_alternates'] = 'No alternate emails found for {$a->fullname}. Continue to make one.';

$string['select_users'] = 'Select Users ...';
$string['select_groups'] = 'Select Sections ...';

// Config form strings.
$string['allowstudents'] = 'Allow students to use Quickmail';
$string['select_roles'] = 'Roles to filter by';
$string['reset'] = 'Restore System Defaults';

$string['no_type'] = '{$a} is not in the acceptable type viewer. Please use the applciation correctly.';
$string['no_email'] = 'Could not email {$a->firstname} {$a->lastname}.';
$string['no_log'] = 'You have no email history yet.';
$string['no_drafts'] = 'You have no email drafts.';
$string['no_subject'] = 'You must have a subject';
$string['no_course'] = 'Invalid Course with id of {$a}';
$string['no_permission'] = 'You do not have permission to send emails with Quickmail.';
$string['no_users'] = 'There are no users you are capable of emailing.';
$string['no_selected'] = 'You must select some users for emailing.';
$string['not_valid'] = 'This is not a valid email log viewer type: {$a}';
$string['not_valid_user'] = 'You can not view other email history.';
$string['not_valid_action'] = 'You must provide a valid action: {$a}';
$string['not_valid_typeid'] = 'You must provide a valid email for {$a}';
$string['delete_failed'] = 'Failed to delete email';
$string['required'] = 'Please fill in the required fields.';
$string['prepend_class'] = 'Prepend Course name';
$string['prepend_class_desc'] = 'Prepend the course shortname to the subject of
the email.';
$string['courselayout'] = 'Course Layout';
$string['courselayout_desc'] = 'Use _Course_ page layout  when rendering the Quickmail block pages. Enable this setting, if you are getting Moodle form fixed width issues.';

$string['are_you_sure'] = 'Are you sure you want to delete {$a->title}? This action
cannot be reversed.';

// Alternate Email strings.
$string['alternate_new'] = 'Add Alternate Address';
$string['sure'] = 'Are you sure you want to delete {$a->address}? This action cannot be undone.';
$string['valid'] = 'Activation Status';
$string['approved'] = 'Approved';
$string['waiting'] = 'Waiting';
$string['entry_activated'] = 'Alternate email {$a->address} can now be used in {$a->course}.';
$string['entry_key_not_valid'] = 'Activation link is no longer valid for {$a->address}. Continue to resend activation link.';
$string['entry_saved'] = 'Alternate address {$a->address} has been saved.';
$string['entry_success'] = 'An email to verify that the address is valid has been sent to {$a->address}. Instructions on how to activate the address is contained in its contents.';
$string['entry_failure'] = 'An email could not be sent to {$a->address}. Please verify that {$a->address} exists, and try again.';
$string['alternate_from'] = 'Moodle: Quickmail';
$string['alternate_subject'] = 'Alternate email address verification';
$string['alternate_body'] = '
<p>
{$a->fullname} added {$a->address} as an alternate sending address for {$a->course}.
</p>

<p>
The purpose of this email was to verify that this address exists, and the owner
of this address has the appropriate permissions in Moodle.
</p>

<p>
If you wish to complete the verification process, please continue by directing
your browser to the following url: {$a->url}.
</p>

<p>
If the description of this email does not make any sense to you, then you may have
received it by mistake. Simply discard this message.
</p>

Thank you.
';
