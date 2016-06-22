<?php
/**
 * Room new form
 *
 * @package    mod_book
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');

class unicko_delete_form extends moodleform {

    function definition() {
        global $CFG;

        $mform = $this->_form;
        $mform->addElement('hidden', 'roomid');
        $mform->setType('roomid', PARAM_INT);
        $this->add_action_buttons(true, get_string('yes'));
    }
}
