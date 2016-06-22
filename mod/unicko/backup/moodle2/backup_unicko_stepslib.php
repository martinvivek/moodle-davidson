<?php
/**
 * Define all the backup steps that will be used by the backup_unicko_activity_task
 *
 * @package   mod_unicko
 * @category  backup
 * @copyright 2016 Ofir Riss and Eyal Levy, unicko.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define the complete unicko structure for backup, with file and id annotations
 */
class backup_unicko_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the backup structure of the module
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        // Get know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define the root element describing the unicko instance.
        $unicko = new backup_nested_element('unicko', array('id'), array(
            'name', 'intro', 'introformat', 'type', 'roomtime', 'scedule', 'lang', 'textdir', 'allhosts'));

        // If we had more elements, we would build the tree here.

        // Define data sources.
        $unicko->set_source_table('unicko', array('id' => backup::VAR_ACTIVITYID));

        // If we were referring to other tables, we would annotate the relation
        // with the element's annotate_ids() method.

        // Define file annotations (we do not use itemid in this example).
        //$unicko->annotate_files('mod_unicko', 'intro', null);

        // Return the root element (unicko), wrapped into standard activity structure.
        return $this->prepare_activity_structure($unicko);
    }
}

