<?php

if(!function_exists('form_email')){
    function form_email($data = '', $value = '', $extra = ''){
        $defaults = array('type' => 'email', 'name' => ((!is_array($data)) ? $data : ''), 'value' => $value);
        return "<input " . _parse_form_attributes($data, $defaults) . $extra . " />";
    }
}

if(!function_exists('form_url')){
    function form_url($data = '', $value = '', $extra = ''){
        $defaults = array('type' => 'url', 'name' => ((!is_array($data)) ? $data : ''), 'value' => $value);
        return "<input " . _parse_form_attributes($data, $defaults) . $extra . " />";
    }
}

if(!function_exists('form_number')){
    function form_number($data = '', $value = '', $extra = ''){
        $defaults = array('type' => 'number', 'name' => ((!is_array($data)) ? $data : ''), 'value' => $value);
        return "<input " . _parse_form_attributes($data, $defaults) . $extra . " />";
    }
}

?>
