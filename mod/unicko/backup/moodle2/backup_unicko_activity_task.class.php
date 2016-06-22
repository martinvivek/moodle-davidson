<?php
/**
 * Defines backup_unicko_activity_task class
 *
 * @package   mod_unicko
 * @category  backup
 * @copyright 2016 Ofir Riss and Eyal Levy, unicko.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/unicko/backup/moodle2/backup_unicko_stepslib.php');

/**
 * Provides the steps to perform one complete backup of the unicko instance
 */
class backup_unicko_activity_task extends backup_activity_task {

    /**
     * No specific settings for this activity
     */
    protected function define_my_settings() {
    }

    /**
     * Defines a backup step to store the instance data in the unicko.xml file
     */
    protected function define_my_steps() {
        $this->add_step(new backup_unicko_activity_structure_step('unicko_structure', 'unicko.xml'));
    }

    /**
     * Encodes URLs to the index.php and view.php scripts
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts
     * @return string the content with the URLs encoded
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, '/');

        // Link to the list of unickos.
        $search = '/('.$base.'\/mod\/unicko\/index.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@UNICKOINDEX*$2@$', $content);

        // Link to unicko view by moduleid.
        $search = '/('.$base.'\/mod\/unicko\/view.php\?id\=)([0-9]+)/';
        $content = preg_replace($search, '$@UNICKOVIEWBYID*$2@$', $content);

        return $content;
    }
}

