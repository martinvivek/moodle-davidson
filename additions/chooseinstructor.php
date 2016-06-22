<?php


require_once('../config.php');
global $COURSE, $USER;

require_login($COURSE->id); // app for choosing PI for Orni by nurit   // 14/4/16
redirect('http://davidson-web2.weizmann.ac.il/davidsonRp/nbj/moodleChooseGuide/choose.php?userid='. $USER->id);
