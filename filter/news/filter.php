<?php
defined('MOODLE_INTERNAL') || die();
/*
// Import of css file
if (file_exists($CFG->dirroot.'/theme/'.$PAGE->theme->name.'/news.css')) {
	echo '<style type="text/css">';
    echo '	@import url('. $CFG->httpsthemewww.'/'.$PAGE->theme->name.'/news.css);';
	echo '</style>';
} else {
	//echo '<style type="text/css">';
    //echo '	@import url('.$CFG->wwwroot.'/filter/news/news.css);';
	//echo '</style>';
}
*/
/**
* This filter shows a top "x" news from a forum
* This will work ONLY on the first page of your Moodle (for security reasons)
* It uses timed discussion and other parameters
*
* [[forum($forumid(INT),$groupid(INT),$groupingid(INT),$nbpost(INT))]]
*
* @package filter-news
* @category filter
* @author Eric Bugnet
*
*/

/**
* Sort array on a specified field
*
* @param array $records The array to sort
* @param string $field The field used to sort the array
* @param bool $reverse ASC or DESC
* @return array An array wich contains the data sorted
*
* This script is issued from www.php.net
*
*/


/**
* Change all instances of NEWS in the text
*
* @uses $CFG,$COURSE;
* Apply the filter to the text
*
* @see filter_manager::apply_filter_chain()
* @param string $text to be processed by the text
* @param array $options filter options
* @return string text after processing
 */
class filter_news extends moodle_text_filter {


public function filter($text, array $options = array()) {

    global $CFG,$COURSE, $DB, $OUTPUT, $USER;
	$CFG->cachetext = false; // very cpu intensive !!! (hanna 23-4-13)

	// Do a quick check to avoid unnecessary work :  - Is there instance ? - Are we on the first page ?
	if (($COURSE->id == 1) || (strpos($text, '[[forum(') === false)) {
		return $text;
	}
	// There is job to do.... so let's do it !
	$pattern = '/\[\[forum\(([0-9]+),([0-9]+),([0-9]+),([0-9]+)\)\]\]/';
	$moduleid = $DB->get_record('modules', array('name'=> 'forum'));

	// If there is an instance again...
	while (preg_match($pattern, $text, $regs)) {

		// For each instance
		if ($regs[4]>0) {
			$cmid=$regs[1];
			$groupid=$regs[2];
            $groupingid=$regs[3];
			$nbpost=$regs[4];
			$news = '';
			if ($groupid>0) {
                $group_list = $groupid;
			} else {
                $group_list = '-1';
			}
			$nbcaract=100;

            if ($groupingid != 0 AND $groupings_groupsids = $DB->get_records('groupings_groups', array('groupingid'=> $groupingid))) {
                $group_list = '';
                foreach($groupings_groupsids as $ggroup){
                    $group_list .= $ggroup->groupid.",";
                }
                //trim($group_list,",");
                $group_list = substr($group_list, 0, -1);
            }

			// Get the forum ID
			$data = array();
			if ($data = $DB->get_record('course_modules', array( 'id'=> $cmid, 'module'=> $moduleid->id)) ){
				$forumid=$data->instance;

				// Get the discussions
				$discussions = array();
				$i=0;
				$time=time();

				// Get last "x" discussion with timestart and store them in $data array
				$query_with="
					    SELECT
						*
					    FROM
						{$CFG->prefix}forum_discussions
					    WHERE
					    	forum = {$forumid} AND
						timestart <> 0 AND
						groupid IN ({$group_list}) AND
						(timeend > {$time} OR
						timeend = 0)
					    ORDER BY
						timestart DESC
						LIMIT {$nbpost}
				";
				if ($datas = $DB->get_records_sql($query_with)) {
					foreach ($datas as $data) {
						$discussions[$i]["id"]=$data->id;
						$discussions[$i]["userid"]=$data->userid;
						$discussions[$i]["time"]=$data->timestart;
						$discussions[$i]["name"]=$data->name;
						$i++;
					}
				}

                $use_group = ($group_list != '-1') ? " groupid  IN ({$group_list}) AND " : '';

				// Get last "x" discussion without timestart and store them in $data array
				$query_without="
					    SELECT
						*
					    FROM
						{$CFG->prefix}forum_discussions
					    WHERE
					    	forum = {$forumid} AND
						timestart = 0 AND $use_group
						 (timeend > {$time} OR
						timeend = 0)
					    ORDER BY
						timemodified DESC
						LIMIT {$nbpost}
				";

				if ($datas = $DB->get_records_sql($query_without)) {
					foreach ($datas as $data) {
						$discussions[$i]["id"]=$data->id;
						$discussions[$i]["userid"]=$data->userid;
						$discussions[$i]["time"]=$data->timemodified;
						$discussions[$i]["name"]=$data->name;
						$i++;
					}
				}

                //$news .= ' sql='.$query_without.' f count='.count($discussions).' ';
				// Organize  $data array
				// - sort on $discussions["time"] DESC
				$discussions = $this->record_sort($discussions, 'time', true);
				// - select only post nb, not more
				$discussions = array_slice($discussions, 0, $nbpost);

				if ($discussions) {
					// There is posts, let's print them !
					$news .= '<div class="newsfilter"><ul>';
					for ($i=0; $i<count($discussions); $i++) {
						$news .= '<li>';
						if ($user = $DB->get_record('user', array('id'=> $discussions[$i]["userid"])) ) {
//                            $news .= print_user_picture($user->id, $COURSE->id, $user->picture, '16', true, true, '', false); //  'class'=>'profilepicture' ???
                            $news .= $OUTPUT->user_picture ($user, array('courseid'=>$COURSE->id, 'size'=> '16')) ;
						}
						// Make the full name
                        $fullname = $user->firstname.' '.$user->lastname;
						 if ($CFG->fullnamedisplay == 'firstname lastname') {
							 $fullname = $user->firstname.' '.$user->lastname;
						 } else if ($CFG->fullnamedisplay == 'lastname firstname') {
							 $fullname = $user->lastname.' '.$user->firstname;
						 } else if ($CFG->fullnamedisplay == 'firstname') {
							 $fullname = $user->firstname;
						 }
						// Print the link
						$news .= '<a href="#" onclick="window.open(\''.$CFG->wwwroot.'/mod/forum/discuss.php?d='
                            .$discussions[$i]["id"].'\',\'discussion\',\'height=700,width=800\');" title="'
                            .userdate($discussions[$i]["time"],'%d/%m/%y ').'- '.$fullname.'" >';
						$news .= substr($discussions[$i]["name"],0,$nbcaract);
						if (strlen($discussions[$i]["name"]) > $nbcaract) {
							$news .= '...';
						}
						$news .= '</a></li>';
					}
					$news .= '</ul>';
//					$news .= '<a target="_new" href="#" id="youropinion" onclick="window.open(\''.$CFG->wwwroot.'/mod/forum/post.php?forum='.$forumid.'\',\'opinion\',\'height=700,width=800\');" >'.get_string("youropinion").'</a><br/>';  
					$news .= '<a href="#" id="youropinion" onclick="window.open(\''.$CFG->wwwroot.'/mod/forum/post.php?forum='.$forumid.'\',\'opinion\',\'height=900,width=999,scrollbars=yes\');" >'.get_string("youropinion","filter_news").'</a>';  // hanna 9/8/12
					$news .= '<a href="#" id="forumpage" onclick="window.open(\''.$CFG->wwwroot.'/mod/forum/view.php?f='.$forumid.'\',\'opinion\',\'height=900,width=999,scrollbars=yes\');" >'.get_string("forumpage","filter_news").'</a>';  // hanna 9/8/12
					//$news .= '<form id="newdiscussionform" method="get" action="'.$CFG->wwwroot.'/mod/forum/post.php"><input type="hidden" name="forum" value="'.$forumid.'"><input type="submit" value="'.get_string('youropinion').'"></form>';
					$news .= '</div><style>#youropinion {border: 2px outset gray;padding: 2px;text-decoration:none;} #youropinion:hover {background-color: #FDF4AF;}</style>';
					$news .= '<style>#forumpage {border: 2px outset gray;padding: 2px;text-decoration:none;} #forumpage:hover {background-color: #FDF4AF;}</style>';  // hanna 9/8/12
					$stylefile = file_get_contents($CFG->wwwroot.'/filter/news/news.css', FILE_USE_INCLUDE_PATH); // add filter's own styles // nadavkav 10-10-2012
					$news .= '<style>'.$stylefile.'</style>';
				}
			}
			// Change chain in text
			$text = str_replace('[[forum('.$cmid.','.$groupid.','.$groupingid.','.$nbpost.')]]',$news,$text);
		} else {
			break;
		}

	}

	return $text;
}
    protected function record_sort($records, $field, $reverse=false) {
        $hash = array();
        foreach($records as $key => $record) {
            $hash[$record[$field].$key] = $record;
        }
        ($reverse)? krsort($hash) : ksort($hash);
        $records = array();
        foreach($hash as $record) {
            $records []= $record;
        }
        return $records;
    }
}  // end class
