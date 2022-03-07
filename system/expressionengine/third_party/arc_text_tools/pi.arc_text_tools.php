<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
    'pi_name'			=> 'Text Tools',
    'pi_version'		=> '1.0',
    'pi_author'			=> 'CreativeArc - GHC',
    'pi_author_url'		=> 'http://creativearc.com/',
    'pi_description'	=> 'Tools for working with text',
    'pi_usage'			=> Arc_text_tools::usage()
);


class Arc_text_tools {

    public $return_data;

    public function _constructor(){
    }

    /**
    * limit string by characters
    * template param characters = number of characters
    * @return string
    */
    public function limit_characters(){

        // no param, bail
        if(!$chars = ee()->TMPL->fetch_param('characters')){
            return ee()->TMPL->tagdata;
        }

        // set default
        $chars = (!is_numeric($chars) || $chars <= 0) ? 100 : $chars;

        // get rid of tags
        $string = trim(strip_tags(ee()->TMPL->tagdata));

        // if string is too short, bail
        if(strlen($string) <= $chars) {
            return ee()->TMPL->tagdata;
        }
        
        return substr($string, 0, strpos(wordwrap($string, $chars), "\n"));

    }
    
    /**
    * limit string to words
    * template param words = number of words
    * @return string
    */
    public function limit_words(){
        
        // no param, bail
        if(!$words = ee()->TMPL->fetch_param('words')){
            return ee()->TMPL->tagdata;
        }
      
        // set default
        $words = (!is_numeric($words) || $words <= 0) ? 100 : $words;

        // get rid of tags
        $string = trim(strip_tags(ee()->TMPL->tagdata));
        
        return ee()->functions->word_limiter($string, $words);

    }
    
    /**
    * wrap words of string in span
    * template param class = class name to add to span
    * @return string
    */
    function add_span(){
        
        // set param defaults
        $class = ee()->TMPL->fetch_param('class');
        $start = ee()->TMPL->fetch_param('start');
        $end = ee()->TMPL->fetch_param('start');

        // set default
        $chars = (!is_numeric($chars) || $chars <= 0) ? 100 : $chars;

        // get rid of tags
        $string = trim(strip_tags(ee()->TMPL->tagdata));
        
        $oldString = trim(strip_tags(ee()->TMPL->tagdata));
        
        $string = explode(' ', $oldString);

        if(sizeof($string) > 1 ) {
            $string[0] = '<span>' . $string[0] . '</span>';
            return implode(' ', $string);
        } else {
            return $oldString;
        }

    }

    /**
    * Plugin Usage
    * @access public
    * @return string
    */
    public static function usage(){
        ob_start(); 
        ?>
        Wrap anything you want to be processed between the tag pairs.
        HTML Tags will be stripped before processing.

        Limit number of words
        {exp:arc_text_tools:limit_words words="100"}
            text you want processed
        {/exp:arc_text_tools:limit_words}

        Limit number of characters
        {exp:arc_text_tools:limit_characters characters="100"}
            text you want processed
        {/exp:arc_text_tools:limit_characters}

        Note: Will not break words.
        
        Version 1
        ******************
        2.0 compatible

        <?php
        $buffer = ob_get_contents();

        ob_end_clean(); 

        return $buffer;
    }

}
