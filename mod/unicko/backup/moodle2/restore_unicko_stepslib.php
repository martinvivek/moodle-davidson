<?php
/**
 * Define all the restore steps that will be used by the restore_unicko_activity_task
 *
 * @package   mod_unicko
 * @category  backup
 * @copyright 2016 Ofir Riss and Eyal Levy, unicko.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Structure step to restore one unicko activity
 */
class restore_unicko_activity_structure_step extends restore_activity_structure_step {

    /**
     * Defines structure of path elements to be processed during the restore
     *
     * @return array of {@link restore_path_element}
     */
    protected function define_structure() {

        $paths = array();
        $paths[] = new restore_path_element('unicko', '/activity/unicko');

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process the given restore path element data
     *
     * @param array $data parsed element data
     */
    protected function process_unicko($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        if (empty($data->timecreated)) {
            $data->timecreated = time();
        }

        if (empty($data->timemodified)) {
            $data->timemodified = time();
        }

        // Create the unicko instance.
        $newitemid = $DB->insert_record('unicko', $data);
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Post-execution actions
     */
    protected function after_execute() {
        // Add unicko related files, no need to match by itemname (just internally handled context).
        //$this->add_related_files('mod_unicko', 'intro', null);
    }
}

