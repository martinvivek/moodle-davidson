<?php
/**
 * Created a signed URL and redirect to the unicko room
 *
 * @package    mod
 * @subpackage unicko
 * @copyright  2016 Ofir Riss and Eyal Levy, unicko.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/locallib.php');

require_login();

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('rooms', 'unicko'));
$PAGE->set_heading(get_string('rooms', 'unicko'));
$PAGE->set_url('/mod/unicko/rooms.php');
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('rooms', 'unicko'), new moodle_url('/mod/unicko/rooms.php'));

$rooms = unicko_get_private_rooms($USER->id);

echo $OUTPUT->header();

//echo $OUTPUT->heading('<a href="/mod/unicko/new.php">'.get_string('createroom', 'unicko').'</a>');

if (!$rooms) {
    echo $OUTPUT->heading(get_string('noroomsfound', 'unicko'));

    $table = NULL;

} else {
    $strdelete = get_string('delete');
    $strremove = get_string('remove', 'unicko');
    $strenrolusers = get_string('enrolusers', 'unicko');

    $table = new html_table();
    $table->width = "95%";
    $table->head = array ();
    $table->align = array();

    $table->head[] = get_string('roomname', 'unicko');
    $table->align[] = 'left';
    $table->head[] = get_string('course');
    $table->align[] = 'left';
    $table->head[] = get_string('owner', 'unicko');
    $table->align[] = 'left';
    $table->head[] = get_string('members', 'unicko');
    $table->align[] = 'left';
    $table->head[] = get_string('edit');
    $table->align[] = 'center';

    foreach($rooms as $room) {
        $row = array ();
        $row[] = html_writer::link(new moodle_url('/mod/unicko/room.php', array('id'=>$room->roomid)), $room->roomname, array('target'=>'_blank'));
        $row[] = html_writer::link(new moodle_url('/course/view.php', array('id'=>$room->roomid)), $room->courseshortname);
        $row[] = $room->ownerfirstname . ' ' . $room->ownerlastname;
        $row[] = $room->count;
        $buttons = array();
        $deleteurl = new moodle_url('/mod/unicko/delete.php', array('id' => $room->roomid));
        if($room->affiliation == 0) {
            $buttons[] = html_writer::link($deleteurl, html_writer::empty_tag('img', array('src'=>$OUTPUT->pix_url('t/delete'), 'alt'=>$strdelete, 'class'=>'iconsmall')), array('title'=>$strdelete));
            $enrolurl = new moodle_url('/mod/unicko/enrol.php', array('id' => $room->roomid));
            $buttons[] = html_writer::link($enrolurl, html_writer::empty_tag('img', array('src'=>$OUTPUT->pix_url('t/enrolusers'), 'alt'=>$strenrolusers, 'class'=>'iconsmall')), array('title'=>$strenrolusers));
        } else {
            $context = context_course::instance($room->courseid);
            if(has_capability('mod/unicko:leaveprivateroom', $context)) {
                $buttons[] = html_writer::link($deleteurl, html_writer::empty_tag('img', array('src'=>$OUTPUT->pix_url('t/delete'), 'alt'=>$strdelete, 'class'=>'iconsmall')), array('title'=>$strremove));
            }
        }
        $row[] = implode(' ', $buttons);
        $table->data[] = $row;
    }

    echo html_writer::table($table);
}

//echo $OUTPUT->heading('<a href="/mod/unicko/new.php">'.get_string('createroom', 'unicko').'</a>');

echo $OUTPUT->footer();
