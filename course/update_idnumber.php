<?php

  require_once('../config.php');

  $userid    = optional_param('userid', 0, PARAM_INT);
  $courseid  = optional_param('courseid', 0, PARAM_INT);
  $idnumber  = optional_param('idnumber', 0, PARAM_INT);

  $ok = $DB->set_field('user','idnumber',$idnumber,array('id'=>$userid));
  
  redirect($CFG->wwwroot.'/course/view.php?id='.$courseid);

