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

class unicko_new_form extends moodleform {

    function definition() {
        global $CFG;

        $mform = $this->_form;

        $mform->addElement('hidden', 'course', $this->_customdata['course']);
        $mform->setType('course', PARAM_INT);

        $mform->addElement('text', 'name', get_string('roomname', 'unicko')); 
        $mform->setType('name', PARAM_NOTAGS);
        $mform->addRule('name', null, 'required', null, 'client');

        $options=array();
        $options['en']  = get_string('english', 'unicko');
        $options['he']  = get_string('hebrew', 'unicko');
        $options['ar']  = get_string('arabic', 'unicko');
        $mform->addElement('select', 'lang', get_string('lang', 'unicko'), $options);
        $lang = 'en';
        switch($CFG->lang) {
            case 'he':
                $lang = 'he';
                break;
            case 'ar':
                $lang = 'ar';
                break;
            default:
                $lang = 'en';
        }
        $mform->setDefault('lang', $lang);

        $mform->addElement('checkbox', 'allhosts', get_string('allhosts', 'unicko'));
        $mform->setType('allhosts', PARAM_INT);
        $mform->setDefault('allhosts', 1);

        $this->add_action_buttons();
    }
}
