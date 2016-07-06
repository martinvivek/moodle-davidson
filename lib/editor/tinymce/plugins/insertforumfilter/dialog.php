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
 * Displays the TinyMCE popup window to insert a Moodle insertforumfilter
 *
 * @package   tinymce_insertforumfilter
 * @copyright 2010 David Mudrak <david@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('NO_MOODLE_COOKIES', true); // Session not used here.

require(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/config.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/lib/editor/tinymce/plugins/insertforumfilter/dialog.php');

$stringmanager = get_string_manager();

$editor = get_texteditor('tinymce');
$plugin = $editor->get_plugin('insertforumfilter');

$htmllang = get_html_lang();
header('Content-Type: text/html; charset=utf-8');
header('X-UA-Compatible: IE=edge');
?>
<!DOCTYPE html>
<html <?php echo $htmllang ?>
<head>
    <title><?php print_string('insertforumfilter:desc', 'tinymce_insertforumfilter'); ?></title>
    <script type="text/javascript" src="<?php echo $editor->get_tinymce_base_url(); ?>/tiny_mce_popup.js"></script>
    <script type="text/javascript" src="<?php echo $plugin->get_tinymce_file_url('js/dialog.js'); ?>"></script>
</head>
<body>

<form onsubmit="insertforumfilterDialog.insert();return false;" action="#">

<?php
        global $COURSE, $DB;

        $courseid = optional_param('courseid', $COURSE->id, PARAM_INT);

        echo 'Select a forum: <select name="forumid" id="forumid">';
		$forums = $DB->get_records('forum', array('course'=> $courseid ));
		foreach ($forums as $forum) {
            $forumnum = $DB->get_record('course_modules', array('course'=> $courseid , 'module'=>'7', 'instance'=>$forum->id));
            echo '<option value="'.$forumnum->id.'" >'.$forum->name.'</option>';
        }
        echo '</select><br>';

        echo 'Select a group: <select name="groupid" id="groupid">';
        $groups = $DB->get_records('groups', array('courseid'=> $courseid ));
        echo '<option value="0" >No group</option>';
        foreach ($groups as $group) {
            echo '<option value="'.$group->id.'" >'.$group->name.'</option>';
        }
        echo '</select><br>';

        echo 'Select a grouping: <select name="groupingid" id="groupingid">';
        $groupings = $DB->get_records('groupings', array('courseid'=> $courseid ));
        echo '<option value="0" >No grouping</option>';
        foreach ($groupings as $grouping) {
            echo '<option value="'.$grouping->id.'" >'.$grouping->name.'</option>';
        }
        echo '</select><br>';

?>

        <p>Here is a insertforumfilter dialog.</p>
        <p>Number of posts to display: <input id="someval" name="someval" type="text" class="text" /></p>

        <div class="mceActionPanel">
            <input type="button" id="insert" name="insert" value="{#insert}" onclick="insertforumfilterDialog.insert();" />
            <input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
        </div>
    </form>

</body>
</html>
