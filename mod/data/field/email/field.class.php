<?php
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.org                                            //
//                                                                       //
// Copyright (C) 1999-onwards Moodle Pty Ltd  http://moodle.com          //
//                                                                       //
// This program is free software; you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// This program is distributed in the hope that it will be useful,       //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details:                          //
//                                                                       //
//          http://www.gnu.org/copyleft/gpl.html                         //
//                                                                       //
///////////////////////////////////////////////////////////////////////////

class data_field_email extends data_field_base {
    var $type = 'email';

    function display_add_field($recordid = 0, $formdata = null) {
        global $CFG, $DB, $OUTPUT, $PAGE;

        require_once($CFG->dirroot. '/repository/lib.php'); // necessary for the constants used in args

        $args = new stdClass();
        $args->accepted_types = '*';
        $args->return_types = FILE_EXTERNAL;
        $args->context = $this->context;
        $args->env = 'email';
        $fp = new file_picker($args);
        $options = $fp->options;

        $fieldid = 'field_email_'.$options->client_id;

        $straddlink = get_string('choosealink', 'repository');
        $email = '';
        $text = '';
        if ($formdata) {
            $fieldname = 'field_' . $this->field->id . '_0';
            $email = $formdata->$fieldname;
            $fieldname = 'field_' . $this->field->id . '_1';
            if (isset($formdata->$fieldname)) {
                $text = $formdata->$fieldname;
            }
        } else if ($recordid) {
            if ($content = $DB->get_record('data_content', array('fieldid'=>$this->field->id, 'recordid'=>$recordid))) {
                $email  = str_replace('mailto:', '', $content->content);
                $text = $content->content1;
            }
        }

        $autolinkable = !empty($this->field->param1) and empty($this->field->param2);

        $str = '<div title="' . s($this->field->description) . '">';

        $label = '<label for="' . $fieldid . '"><span class="accesshide">' . $this->field->name . '</span>';
        if ($this->field->required) {
            $image = html_writer::img($OUTPUT->pix_url('req'), get_string('requiredelement', 'form'),
                                      array('class' => 'req', 'title' => get_string('requiredelement', 'form')));
            if ($autolinkable) {
                $label .= html_writer::div(get_string('requiredelement', 'form'), 'accesshide');
            } else {
                $label .= html_writer::div($image, 'inline-req');
            }
        }
        $label .= '</label>';

        if ($autolinkable) {
            $str .= '<table><tr><td align="right">';
            $str .= '<span class="mod-data-input">' . get_string('email', 'data') . ':</span>';
            if (!empty($image)) {
                $str .= $image;
            }
            $str .= '</td><td>';
            $str .= $label;
            $str .= '<input type="text" name="field_'.$this->field->id.'_0" id="'.$fieldid.'" value="'.$email.'" size="60" />';
            $str .= '<button id="filepicker-button-'.$options->client_id.'" style="display:none">'.$straddlink.'</button></td></tr>';
            $str .= '<tr><td align="right"><span class="mod-data-input">'.get_string('text', 'data').':</span></td><td>';
            $str .= '<input type="text" name="field_'.$this->field->id.'_1" id="field_'.$this->field->id.'_1" value="'.s($text).'"';
            $str .= ' size="60" /></td></tr>';
            $str .= '</table>';
        } else {
            // Just the email field
            $str .= $label;
            $str .= '<input type="text" name="field_'.$this->field->id.'_0" id="'.$fieldid.'" value="'.s($email).'"';
            $str .= ' size="60" class="mod-data-input" />';
            if (count($options->repositories) > 0) {
                $str .= '<button id="filepicker-button-'.$options->client_id.'" class="visibleifjs">'.$straddlink.'</button>';
            }
        }

        // print out file picker
        //$str .= $OUTPUT->render($fp);

        $module = array('name'=>'data_urlpicker', 'fullpath'=>'/mod/data/data.js', 'requires'=>array('core_filepicker'));
        $PAGE->requires->js_init_call('M.data_urlpicker.init', array($options), true, $module);
        $str .= '</div>';
        return $str;
    }

    function display_search_field($value = '') {
        return '<label class="accesshide" for="f_'.$this->field->id.'">' . get_string('fieldname', 'data') . '</label>' .
               '<input type="text" size="16" id="f_'.$this->field->id.'" name="f_'.$this->field->id.'" value="'.$value.'" />';
    }

    function parse_search_field() {
        return optional_param('f_'.$this->field->id, '', PARAM_NOTAGS);
    }

    function generate_sql($tablealias, $value) {
        global $DB;

        static $i=0;
        $i++;
        $name = "df_email_$i";
        return array(" ({$tablealias}.fieldid = {$this->field->id} AND ".$DB->sql_like("{$tablealias}.content",
                ":$name", false).") ", array($name=>"%$value%"));
    }

    function display_browse_field($recordid, $template) {
        global $DB;

        if ($content = $DB->get_record('data_content', array('fieldid'=>$this->field->id, 'recordid'=>$recordid))) {
            $email = empty($content->content)? '' : str_replace('mailto:', '', $content->content);
            $text = empty($content->content1)? '' : $content->content1;
            if (empty($email) or ($email == 'mailto:')) {
                return '';
            }
            if (!empty($this->field->param2)) {
                // param2 forces the text to something
                $text = $this->field->param2;
            }
            if ($this->field->param1) {
                // param1 defines whether we want to autolink the email.
                $attributes = array();
                if ($this->field->param3) {
                    // param3 defines whether this email should open in a new window.
                    $attributes['target'] = '_blank';
                }

                if (empty($text)) {
                    $text = $email;
                }

                $str = html_writer::link($email, $text, $attributes);
            } else {
                $str = $email;
            }
            return $str;
        }
        return false;
    }

    function update_content($recordid, $value, $name='') {
        global $DB;

        $content = new stdClass();
        $content->fieldid = $this->field->id;
        $content->recordid = $recordid;
        $names = explode('_', $name);

        switch ($names[2]) {
            case 0:
                // update link
                $content->content = clean_param($value, PARAM_EMAIL);
                break;
            case 1:
                // add text
                $content->content1 = clean_param($value, PARAM_NOTAGS);
                break;
            default:
                break;
        }

        if (!empty($content->content) && (strpos($content->content, 'mailto:') === false)) {
            $content->content = 'mailto:' . $content->content;
        }

        if ($oldcontent = $DB->get_record('data_content', array('fieldid'=>$this->field->id, 'recordid'=>$recordid))) {
            $content->id = $oldcontent->id;
            return $DB->update_record('data_content', $content);
        } else {
            return $DB->insert_record('data_content', $content);
        }
    }

    function notemptyfield($value, $name) {
        $names = explode('_',$name);
        $value = clean_param($value, PARAM_EMAIL);
        //clean first
        if ($names[2] == '0') {
            return ($value!='mailto:' && !empty($value));
        }
        return false;
    }

    function export_text_value($record) {
        return $record->content . " " . $record->content1;
    }

}
