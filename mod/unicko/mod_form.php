<?php
/**
 * The main unicko configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__).'/locallib.php');
require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 */
class mod_unicko_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        //-------------------------------------------------------------------------------
        // Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('roomname', 'unicko'), array('size'=>'64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        //$mform->addHelpButton('name', 'roomname', 'unicko');

        // Adding the standard "intro" and "introformat" fields
        if(method_exists($this, 'standard_intro_elements')) {
            // Moodle 2.9
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor(true);
        }

        $mform->addElement('date_time_selector', 'roomtime', get_string('roomtime', 'unicko'));

        $options=array();
        $options[0]  = get_string('donotuseroomtime', 'unicko');
        $options[1]  = get_string('repeatnone', 'unicko');
        $options[2]  = get_string('repeatdaily', 'unicko');
        $options[3]  = get_string('repeatweekly', 'unicko');
        $mform->addElement('select', 'schedule', get_string('repeattimes', 'unicko'), $options);

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
        $mform->setDefault('allhosts', 0);

        //-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
        //-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();
    }
}
