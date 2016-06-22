<?php
/**
 * Library of interface functions and constants for module unicko
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 * All the unicko specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/calendar/lib.php');

defined('MOODLE_INTERNAL') || die();

/** example constant */
//define('NEWMODULE_ULTIMATE_ANSWER', 42);

////////////////////////////////////////////////////////////////////////////////
// Moodle core API                                                            //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function unicko_supports($feature) {
    switch($feature) {
        case FEATURE_GROUPS:            return false;
        case FEATURE_GROUPINGS:         return false;
        case FEATURE_GROUPMEMBERSONLY:  return false;
        case FEATURE_MOD_INTRO:         return true;
        case FEATURE_SHOW_DESCRIPTION:  return true;
        case FEATURE_GRADE_HAS_GRADE:   return false;
        case FEATURE_GRADE_OUTCOMES:    return false;
        case FEATURE_BACKUP_MOODLE2:    return true;
        default:                        return null;
    }
}

/**
 * Saves a new instance of the unicko into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $unicko An object from the form in mod_form.php
 * @param mod_unicko_mod_form $mform
 * @return int The id of the newly inserted unicko record
 */
function unicko_add_instance(stdClass $unicko, mod_unicko_mod_form $mform = null) {
    global $DB;

    $unicko->type = 0; // activity
    $unicko->timecreated = time();
    $unicko->textdir = ($unicko->lang == 'he' || $unicko->lang == 'ar') ? 1 : 0;
    $unicko->allhosts = isset($unicko->allhosts) ? $unicko->allhosts : 0;
    $returnid = $DB->insert_record('unicko', $unicko);

    if($unicko->schedule > 0) {
        $event = new stdClass();
        $event->name        = $unicko->name;
        $event->description = format_module_intro('unicko', $unicko, $unicko->coursemodule);
        $event->courseid    = $unicko->course;
        $event->groupid     = 0;
        $event->userid      = 0;
        $event->modulename  = 'unicko';
        $event->instance    = $returnid;
        $event->eventtype   = 'roomtime';
        $event->timestart   = $unicko->roomtime;
        $event->timeduration = 0;

        calendar_event::create($event);
    }

    return $returnid;
}

/**
 * Updates an instance of the unicko in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $unicko An object from the form in mod_form.php
 * @param mod_unicko_mod_form $mform
 * @return boolean Success/Fail
 */
function unicko_update_instance(stdClass $unicko, mod_unicko_mod_form $mform = null) {
    global $DB;

    $unicko->type = 0; // activity
    $unicko->timemodified = time();
    $unicko->textdir = ($unicko->lang == 'he' || $unicko->lang == 'ar') ? 1 : 0;
    $unicko->allhosts = isset($unicko->allhosts) ? $unicko->allhosts : 0;
    $unicko->id = $unicko->instance;

    $DB->update_record('unicko', $unicko);

    if($unicko->schedule == 0) {
        $DB->delete_records('event', array('modulename'=>'unicko', 'instance'=>$unicko->id));
    } else {
        $event = new stdClass();
        if ($event->id = $DB->get_field('event', 'id', array('modulename'=>'unicko', 'instance'=>$unicko->id))) {

            $event->name        = $unicko->name;
            $event->description = format_module_intro('unicko', $unicko, $unicko->coursemodule);
            $event->timestart   = $unicko->roomtime;

            $calendarevent = calendar_event::load($event->id);
            $calendarevent->update($event);
        } else {
            $event->name        = $unicko->name;
            $event->description = format_module_intro('unicko', $unicko, $unicko->coursemodule);
            $event->courseid    = $unicko->course;
            $event->groupid     = 0;
            $event->userid      = 0;
            $event->modulename  = 'unicko';
            $event->instance    = $unicko->id;
            $event->eventtype   = 'roomtime';
            $event->timestart   = $unicko->roomtime;
            $event->timeduration = 0;

            calendar_event::create($event);
        }
    }

    return true;
}

/**
 * Removes an instance of the unicko from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function unicko_delete_instance($id) {
    global $DB;

    if (! $unicko = $DB->get_record('unicko', array('id' => $id))) {
        return false;
    }

    $result = true;

    if (! $DB->delete_records('unicko', array('id' => $unicko->id))) {
        $result = false;
    }

    if (! $DB->delete_records('event', array('modulename'=>'unicko', 'instance'=>$unicko->id))) {
        $result = false;
    }

    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return stdClass|null
 */
function unicko_user_outline($course, $user, $mod, $unicko) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $unicko the module instance record
 * @return void, is supposed to echp directly
 */
function unicko_user_complete($course, $user, $mod, $unicko) {
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in unicko activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 */
function unicko_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link unicko_print_recent_mod_activity()}.
 *
 * @param array $activities sequentially indexed array of objects with the 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 * @return void adds items into $activities and increases $index
 */
function unicko_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@see unicko_get_recent_mod_activity()}

 * @return void
 */
function unicko_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function unicko_cron () {
    unicko_update_unicko_times();
    return true;
}

/**
 * Returns all other caps used in the module
 *
 * @example return array('moodle/site:accessallgroups');
 * @return array
 */
function unicko_get_extra_capabilities() {
    return array();
}

////////////////////////////////////////////////////////////////////////////////
// Gradebook API                                                              //
////////////////////////////////////////////////////////////////////////////////

/**
 * Is a given scale used by the instance of unicko?
 *
 * This function returns if a scale is being used by one unicko
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $unickoid ID of an instance of this module
 * @return bool true if the scale is used by the given unicko instance
 */
function unicko_scale_used($unickoid, $scaleid) {
    return false;
}

/**
 * Checks if scale is being used by any instance of unicko.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param $scaleid int
 * @return boolean true if the scale is used by any unicko instance
 */
function unicko_scale_used_anywhere($scaleid) {
    return false;
}

/**
 * Creates or updates grade item for the give unicko instance
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $unicko instance object with extra cmidnumber and modname property
 * @return void
 */
function unicko_grade_item_update(stdClass $unicko) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $item = array();
    $item['itemname'] = clean_param($unicko->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;
    $item['grademax']  = $unicko->grade;
    $item['grademin']  = 0;

    grade_update('mod/unicko', $unicko->course, 'mod', 'unicko', $unicko->id, 0, null, $item);
}

/**
 * Update unicko grades in the gradebook
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $unicko instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 * @return void
 */
function unicko_update_grades(stdClass $unicko, $userid = 0) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $grades = array(); // populate array of grade objects indexed by userid

    grade_update('mod/unicko', $unicko->course, 'mod', 'unicko', $unicko->id, 0, $grades);
}

////////////////////////////////////////////////////////////////////////////////
// File API                                                                   //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function unicko_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * File browsing support for unicko file areas
 *
 * @package mod_unicko
 * @category files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info instance or null if not found
 */
function unicko_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the unicko file areas
 *
 * @package mod_unicko
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the unicko's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function unicko_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options=array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

////////////////////////////////////////////////////////////////////////////////
// Navigation API                                                             //
////////////////////////////////////////////////////////////////////////////////

/**
 * Extends the global navigation tree by adding unicko nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the unicko module instance
 * @param stdClass $course
 * @param stdClass $module
 * @param cm_info $cm
 */
function unicko_extend_navigation(navigation_node $navref, stdclass $course, stdclass $module, cm_info $cm) {
}

/**
 * Extends the settings navigation with the unicko settings
 *
 * This function is called when the context for the page is a unicko module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@link settings_navigation}
 * @param navigation_node $unickonode {@link navigation_node}
 */
function unicko_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $unickonode=null) {
}

/**
 * This standard function will check all instances of this module
 * and make sure there are up-to-date events created for each of them.
 * If courseid = 0, then every room event in the site is checked, else
 * only room events belonging to the course specified are checked.
 * This function is used, in its new format, by restore_refresh_events()
 *
 * @global object
 * @param int $courseid
 * @return bool
 */
function unicko_refresh_events($courseid = 0) {
    global $DB;

    if ($courseid) {
        if (! $unickos = $DB->get_records("unicko", array("course"=>$courseid))) {
            return true;
        }
    } else {
        if (! $unickos = $DB->get_records("unicko", array("type"=>0))) {
            return true;
        }
    }
    $moduleid = $DB->get_field('modules', 'id', array('name'=>'unicko'));

    foreach ($unickos as $unicko) {
        $cm = get_coursemodule_from_id('unicko', $unicko->id);
        $event = new stdClass();
        $event->name        = $unicko->name;
        $event->description = format_module_intro('unicko', $unicko, $cm->id);
        $event->timestart   = $unicko->roomtime;

        if ($event->id = $DB->get_field('event', 'id', array('modulename'=>'unicko', 'instance'=>$unicko->id))) {
            $calendarevent = calendar_event::load($event->id);
            $calendarevent->update($event);
        } else {
            $event->courseid    = $unicko->course;
            $event->groupid     = 0;
            $event->userid      = 0;
            $event->modulename  = 'unicko';
            $event->instance    = $unicko->id;
            $event->eventtype   = 'roomtime';
            $event->timeduration = 0;
            $event->visible     = $DB->get_field('course_modules', 'visible', array('module'=>$moduleid, 'instance'=>$unicko->id));

            calendar_event::create($event);
        }
    }
    return true;
}

/**
 * Updates unicko records so that the next room time is correct
 *
 * @global object
 * @param int $unickoid
 * @return void
 */
function unicko_update_unicko_times($unickoid=0) {
    global $DB;

    $timenow = time();

    $params = array('timenow'=>$timenow, 'unickoid'=>$unickoid);

    if ($unickoid) {
        if (!$unickos[] = $DB->get_record_select("unicko", "id = :unickoid AND roomtime <= :timenow AND schedule > 0", $params)) {
            return;
        }
    } else {
        if (!$unickos = $DB->get_records_select("unicko", "roomtime <= :timenow AND schedule > 0", $params)) {
            return;
        }
    }

    foreach ($unickos as $unicko) {
        switch ($unicko->schedule) {
            case 1: // Single event - turn off schedule and disable
                    $unicko->roomtime = 0;
                    $unicko->schedule = 0;
                    break;
            case 2: // Repeat daily
                    while ($unicko->roomtime <= $timenow) {
                        $unicko->roomtime += 24 * 3600;
                    }
                    break;
            case 3: // Repeat weekly
                    while ($unicko->roomtime <= $timenow) {
                        $unicko->roomtime += 7 * 24 * 3600;
                    }
                    break;
        }
        $DB->update_record("unicko", $unicko);

        $event = new stdClass();           // Update calendar too

        $cond = "modulename='unicko' AND instance = :unickoid AND timestart <> :roomtime";
        $params = array('roomtime'=>$unicko->roomtime, 'unickoid'=>$unickoid);

        if ($event->id = $DB->get_field_select('event', 'id', $cond, $params)) {
            $event->timestart   = $unicko->roomtime;
            $calendarevent = calendar_event::load($event->id);
            $calendarevent->update($event, false);
        }
    }
}

/**
* Given a course_module object, this function returns any
* "extra" information that may be needed when printing
* this activity in a course listing.
* See get_array_of_activities() in course/lib.php
*
* @global object
* @param object $cm
* @return object|null
*/
function unicko_get_coursemodule_info($cm) {
    global $DB;

    if (!$unicko = $DB->get_record('unicko', array('id' => $cm->instance),
            'name')) {
        return null;
    }

    $info = new cached_cm_info();
    $url = new moodle_url('/mod/unicko/view.php', array('id' => $cm->id));
    $info->onclick = "window.open('$url'); return false;";
    return $info;
}

/**
 * Sets dynamic information about a course module
 *
 * This function is called from cm_info when displaying the module
 * mod_folder can be displayed inline on course page and therefore have no course link
 *
 * @param cm_info $cm
 */
function unicko_cm_info_dynamic(cm_info $cm) {
    $context = context_module::instance($cm->id);
    $recordingsURL = new moodle_url('/mod/unicko/recordings.php', array('id' => $cm->id));
    if(has_capability('mod/unicko:manage', $context)) {
        $manageURL = new moodle_url('/mod/unicko/manage.php', array('id' => $cm->id));        
        $content = '<a href="' . $recordingsURL . '" target="_blank">' . get_string('recordings', 'unicko') . '</a>'
                 . '&nbsp|&nbsp'
                 . '<a href="' . $manageURL . '" target="_blank">' . get_string('reports', 'unicko') . '</a>';
        $cm->set_content($content);
    } else if(has_capability('mod/unicko:viewrecording', $context)) {
        $content = '<a href="' . $recordingsURL . '" target="_blank">' . get_string('recordings', 'unicko') . '</a>';
        $cm->set_content($content);
    }
}
