<?php

if (file_exists($CFG->dirroot . "/blocks/course_overview/renderer.php")) {
    require_once($CFG->dirroot . "/blocks/course_overview/renderer.php");

    define ('SORTCOURSESBY_ABC', 0);
    define ('SORTCOURSESBY_LASTACCESS', 1);

    class theme_essential_block_course_overview_renderer extends block_course_overview_renderer {

        /**
         * Construct contents of course_overview block
         *
         * @param array $courses list of courses in sorted order
         * @param array $overviews list of course overviews
         * @return string html to be displayed in course_overview block
         */
        public function course_overview($courses, $overviews) {
            global $USER;
            $html = '';
            $config = get_config('block_course_overview');
            if ($config->showcategories != BLOCKS_COURSE_OVERVIEW_SHOWCATEGORIES_NONE) {
                global $CFG;
                require_once($CFG->libdir.'/coursecatlib.php');
            }
            $ismovingcourse = false;
            $courseordernumber = 0;
            $maxcourses = count($courses);
            $userediting = false;
            // Intialise string/icon etc if user is editing and courses > 1
            if ($this->page->user_is_editing() && (count($courses) > 1)) {
                $userediting = true;
                $this->page->requires->js_init_call('M.block_course_overview.add_handles');

                // Check if course is moving
                $ismovingcourse = optional_param('movecourse', FALSE, PARAM_BOOL);
                $movingcourseid = optional_param('courseid', 0, PARAM_INT);
            }

            // Render first movehere icon.
            if ($ismovingcourse) {
                // Remove movecourse param from url.
                $this->page->ensure_param_not_in_url('movecourse');

                // Show moving course notice, so user knows what is being moved.
                $html .= $this->output->box_start('notice');
                $a = new stdClass();
                $a->fullname = $courses[$movingcourseid]->fullname;
                $a->cancellink = html_writer::link($this->page->url, get_string('cancel'));
                $html .= get_string('movingcourse', 'block_course_overview', $a);
                $html .= $this->output->box_end();

                $moveurl = new moodle_url('/blocks/course_overview/move.php',
                    array('sesskey' => sesskey(), 'moveto' => 0, 'courseid' => $movingcourseid));
                // Create move icon, so it can be used.
                $movetofirsticon = html_writer::empty_tag('img',
                    array('src' => $this->output->pix_url('movehere'),
                        'alt' => get_string('movetofirst', 'block_course_overview', $courses[$movingcourseid]->fullname),
                        'title' => get_string('movehere')));
                $moveurl = html_writer::link($moveurl, $movetofirsticon);
                $html .= html_writer::tag('div', $moveurl, array('class' => 'movehere'));
            }

            $sortcoursesby = optional_param('sortcoursesby', SORTCOURSESBY_LASTACCESS, PARAM_INT);
            $selectedsort_abc = '';
            $selectedsort_lastaccess = '';
            switch ($sortcoursesby) {
                case SORTCOURSESBY_ABC:
                    // Sort by course fullname
                    usort($courses, function($a, $b) { return strcmp($a->fullname, $b->fullname); });
                    $selectedsort_abc = 'selected';

                    break;
                case SORTCOURSESBY_LASTACCESS:
                    // Sort by user's lastaccess to course
                    //usort($courses, function($a, $b) { return $a->lastaccess - $b->lastaccess; });

                default:
                    global $DB;
                    $lastaccesscourses = $DB->get_records('user_lastaccess', array('userid'=>$USER->id), 'timeaccess DESC');
                    foreach ($lastaccesscourses as $c) {
                        if (isset($courses[$c->courseid])) {
                            $courses[$c->courseid]->lastaccess = $c->timeaccess;
                        }
                    }
                    // Sort by user's lastaccess to course
                    usort($courses, function($a, $b) { return $b->lastaccess - $a->lastaccess; });
                    $selectedsort_lastaccess = 'selected';

            }

            $sortcoursesurl = new moodle_url('/my/index.php', array('sortcoursesby' => SORTCOURSESBY_LASTACCESS)); // Fix NGINX missing PHP PATH_INFO
            //$sortcoursesurl = new moodle_url('/my?sortcoursesby='.SORTCOURSESBY_LASTACCESS); // Used to work just fine on Apache :-(
            $sortcoursesurlhtml = html_writer::link($sortcoursesurl, get_string('sortbylastaccess', 'theme_essential'), array('class' => 'btn '.$selectedsort_lastaccess));
            $html .= html_writer::tag('div', $sortcoursesurlhtml, array('class' => 'sortbylastaccess buttonz'));

            $sortcoursesurl = new moodle_url('/my/index.php', array('sortcoursesby' => SORTCOURSESBY_ABC));
            //$sortcoursesurl = new moodle_url('/my?sortcoursesby='.SORTCOURSESBY_ABC);
            $sortcoursesurlhtml = html_writer::link($sortcoursesurl, get_string('sortbyabc', 'theme_essential'), array('class' => 'btn '.$selectedsort_abc));
            $html .= html_writer::tag('div', $sortcoursesurlhtml, array('class' => 'sortbyabc buttonz'));

            $html .= html_writer::tag('hr', '',array('style'=>'clear:both;'));

            foreach ($courses as $key => $course) {
                $userrolesincourse = get_arrayuser_roles_in_course($USER->id,$course->id); // user who is a student only won't see the course on myview   nadavkav  28/5/13 neither groupteacherviewer 13/4/15
                if ( !( mb_substr($course->fullname, 0, 1) == "*" )

                    OR ( !( count($userrolesincourse) == 1
                         AND (array_key_exists('5', $userrolesincourse ) OR array_key_exists('10', $userrolesincourse )
                           OR array_key_exists('4', $userrolesincourse ) OR array_key_exists('11', $userrolesincourse ) ) ) )
                    
                    OR is_siteadmin()) { // students do not see courses with * in name   hanna 13/8/15
                    // If moving course, then don't show course which needs to be moved.
                    if ($ismovingcourse && ($course->id == $movingcourseid)) {
                        continue;
                    }
                    $html .= $this->output->box_start('coursebox', "course-{$course->id}");
                    $html .= html_writer::start_tag('div', array('class' => 'course_title'));
                    // If user is editing, then add move icons.
                    if ($userediting && !$ismovingcourse) {
                        $moveicon = html_writer::empty_tag('img',
                            array('src' => $this->pix_url('t/move')->out(false),
                                'alt' => get_string('movecourse', 'block_course_overview', $course->fullname),
                                'title' => get_string('move')));
                        $moveurl = new moodle_url($this->page->url, array('sesskey' => sesskey(), 'movecourse' => 1, 'courseid' => $course->id));
                        $moveurl = html_writer::link($moveurl, $moveicon);
                        $html .= html_writer::tag('div', $moveurl, array('class' => 'move'));

                    }

                    // No need to pass title through s() here as it will be done automatically by html_writer.
                    $attributes = array('title' => $course->fullname);
                    if ($course->id > 0) {
                        if (empty($course->visible)) {
                            $attributes['class'] = 'dimmed';
                        }
                        $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
                        $coursefullname = format_string(get_course_display_name_for_list($course), true, $course->id);
                        $link = html_writer::link($courseurl, $coursefullname, $attributes);
                        //$html .= $this->output->heading($link, 2, 'title'); // nadavkav
                    } else {
                        $html .= $this->output->heading(html_writer::link(
                                new moodle_url('/auth/mnet/jump.php', array('hostid' => $course->hostid, 'wantsurl' => '/course/view.php?id=' . $course->remoteid)),
                                format_string($course->shortname, true), $attributes) . ' (' . format_string($course->hostname) . ')', 2, 'title');
                    }

                    // todo: remove courses with * from the list
                    if ($course instanceof stdClass) {
                        global $CFG;
                        require_once($CFG->libdir. '/coursecatlib.php');
                        $course = new course_in_list($course);
                    }
                    if (!$course->has_course_overviewfiles()) {
                        $nocoursesummaryimg = html_writer::img(new moodle_url('/theme/essential/pix_plugins/blocks/course_overview/default_nocoursesummary.png'), '', array('width' => '295px'));
                        $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
                        $coursefullname = html_writer::tag('span',format_string(get_course_display_name_for_list($course), true, $course->id),array('class' => 'name'));
                        $link = html_writer::link($courseurl, $nocoursesummaryimg . '<br>' . $coursefullname, $attributes);
                        $html .= $this->output->heading($link, 2, 'title');
                    } else {
                        // Get image file from course public summary files.
                        // (Should be only one)
                        foreach ($course->get_course_overviewfiles() as $file) {
                            $isimage = $file->is_valid_image();
                            $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                                '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                                $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                            if ($isimage) {
                                $courseimage = html_writer::tag('div',
                                    html_writer::empty_tag('img', array('src' => $url)),
                                    array('class' => 'courseimage'));
                            }
                        }
                        //$shortcoursesummary = mb_substr(format_text($course->summary,FORMAT_PLAIN),20);
                        // nadavkav. Display summary - image + text inside table.
                        $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
                        $coursefullname = format_string(get_course_display_name_for_list($course), true, $course->id);
                        $coursefullname = html_writer::tag('span', $coursefullname, array('class' => 'titlename'));
                        $link = html_writer::link($courseurl, $courseimage . /*$shortcoursesummary. */ $coursefullname, $attributes);
                        $html .= html_writer::tag('div', $link, array('class' => 'summary'));
                    }


                    $html .= $this->output->box('', 'flush');
                    $html .= html_writer::end_tag('div');

                    if (!empty($config->showchildren) && ($course->id > 0)) {
                        // List children here.
                        if ($children = block_course_overview_get_child_shortnames($course->id)) {
                            $html .= html_writer::tag('span', $children, array('class' => 'coursechildren'));
                        }
                    }

                    // If user is moving courses, then down't show overview.
                    if (isset($overviews[$course->id]) && !$ismovingcourse) {
                        $html .= $this->activity_display($course->id, $overviews[$course->id]);
                    }

                    if ($config->showcategories != BLOCKS_COURSE_OVERVIEW_SHOWCATEGORIES_NONE) {
                        // List category parent or categories path here.
                        $currentcategory = coursecat::get($course->category, IGNORE_MISSING);
                        if ($currentcategory !== null) {
                            $html .= html_writer::start_tag('div', array('class' => 'categorypath'));
                            if ($config->showcategories == BLOCKS_COURSE_OVERVIEW_SHOWCATEGORIES_FULL_PATH) {
                                foreach ($currentcategory->get_parents() as $categoryid) {
                                    $category = coursecat::get($categoryid, IGNORE_MISSING);
                                    if ($category !== null) {
                                        $html .= $category->get_formatted_name() . ' / ';
                                    }
                                }
                            }
                            $html .= $currentcategory->get_formatted_name();
                            $html .= html_writer::end_tag('div');
                        }
                    }

                    $html .= $this->output->box('', 'flush');
                    $html .= $this->output->box_end();
                    $courseordernumber++;
                    if ($ismovingcourse) {
                        $moveurl = new moodle_url('/blocks/course_overview/move.php',
                            array('sesskey' => sesskey(), 'moveto' => $courseordernumber, 'courseid' => $movingcourseid));
                        $a = new stdClass();
                        $a->movingcoursename = $courses[$movingcourseid]->fullname;
                        $a->currentcoursename = $course->fullname;
                        $movehereicon = html_writer::empty_tag('img',
                            array('src' => $this->output->pix_url('movehere'),
                                'alt' => get_string('moveafterhere', 'block_course_overview', $a),
                                'title' => get_string('movehere')));
                        $moveurl = html_writer::link($moveurl, $movehereicon);
                        $html .= html_writer::tag('div', $moveurl, array('class' => 'movehere'));
                    }
                } //  end if roles
            }   //  end foreach
            // Wrap course list in a div and return.
            return html_writer::tag('div', $html, array('class' => 'course_list', 'ontouchstart'=>"this.classList.toggle('hover')"));
        }
    }

}