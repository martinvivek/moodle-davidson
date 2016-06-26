<?php

class coursemetadata_field_menu extends coursemetadata_field_base {
    var $options;
    var $datakey;

    /**
     * Constructor method.
     * Pulls out the options for the menu from the database and sets the
     * the corresponding key for the data if it exists
     */
    function coursemetadata_field_menu($fieldid=0, $courseid=0) {
        //first call parent constructor
        $this->coursemetadata_field_base($fieldid, $courseid);

        /// Param 1 for menu type is the options
        $options = explode("\n", $this->field->param1);
        $this->options = array();
        if ($this->field->required){
            $this->options[''] = get_string('choose').'...';
        }
        foreach($options as $key => $option) {
            $this->options[$key] = format_string($option);//multilang formatting
        }

        /// Set the data key
        if ($this->data !== NULL) {
            $this->datakey = (int)array_search($this->data, $this->options);
        }
    }

    /**
     * Create the code snippet for this field instance
     * Overwrites the base class method
     * @param   object   moodleform instance
     */
    function edit_field_add($mform) {
        $mform->addElement('select', $this->inputname, format_string($this->field->name), $this->options);
    }

    /**
     * Set the default value for this field instance
     * Overwrites the base class method
     */
    function edit_field_set_default(&$mform) {
        if (FALSE !==array_search($this->field->defaultdata, $this->options)){
            $defaultkey = (int)array_search($this->field->defaultdata, $this->options);
        } else {
            $defaultkey = '';
        }
        $mform->setDefault($this->inputname, $defaultkey);
    }

    /**
     * The data from the form returns the key. This should be converted to the
     * respective option string to be saved in database
     * Overwrites base class accessor method
     * @param   integer   the key returned from the select input in the form
     */
    //function edit_save_data_preprocess($key) {  // fix nadavkav 21/7/14
    function edit_save_data_preprocess($data, &$datarecord) {
        return isset($this->options[$data]) ? $this->options[$data] : NULL;
        //return isset($data[$key]) ? $data[$key] : null;
        /*
        if (is_array($data)) {
            $datarecord->dataformat = $data['format'];
            $data = $data['text'];
        }
        return $data;
        */
    }

    /**
     * When passing the user object to the form class for the edit profile page
     * we should load the key for the saved data
     * Overwrites the base class method
     * @param   object   user object
     */
    function edit_load_course_data(&$course) {
        $course->{$this->inputname} = $this->datakey;
    }

    /**
     * HardFreeze the field if locked.
     * @param   object   instance of the moodleform class
     */
    function edit_field_set_locked(&$mform) {
        if (!$mform->elementExists($this->inputname)) {
            return;
        }
        // fix nadavkav 21/7/14
        if ($this->is_locked() and !has_capability('moodle/course:update', context_system::instance())) {
            $mform->hardFreeze($this->inputname);
            $mform->setConstant($this->inputname, $this->datakey);
        }
    }
}


