<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
    'pi_name'			=> 'Word Limiter',
    'pi_version'		=> '1.2',
    'pi_author'			=> 'Rick Ellis - GHC Modified',
    'pi_author_url'		=> 'http://expressionengine.com/',
    'pi_description'	=> 'Permits you to limit the number of words in some text, or characters',
    'pi_usage'			=> Word_limit::usage()
);


/**
* Word_limit Class
*
* @package			ExpressionEngine
* @category		Plugin
* @author			ExpressionEngine Dev Team
* @copyright		Copyright (c) 2004 - 2009, EllisLab, Inc.
* @link			http://expressionengine.com/downloads/details/word_limiter/
*/

class Word_limit {

    var $return_data;

    /**
    * Constructor
    */
    function _constructor(){
    }

    function limit_characters(){

        $length = ee()->TMPL->fetch_param('char');

        $string = trim(strip_tags(ee()->TMPL->tagdata));

        if (strlen($string) <= $length) {
            $string = $string; //do nothing
        } else {
            $string = substr($string, 0, strpos(wordwrap($string, $length), "\n"));
        }

        return $string;

    }
    
    function limit_words(){

        $total = (!ee()->TMPL->fetch_param('total')) ? 500 : ee()->TMPL->fetch_param('total');        
        $total = (!is_numeric($total)) ? 500 : $total;

        $string = trim(strip_tags(ee()->TMPL->tagdata));
        
        return ee()->functions->word_limiter($string, $total);

    }



    // --------------------------------------------------------------------

    /**
    * Usage
    *
    * Plugin Usage
    *
    * @access	public
    * @return	string
    */
    public static function usage(){
        ob_start(); 
        ?>
        Wrap anything you want to be processed between the tag pairs.
        HTML Tags will be stripped before processing.

        {exp:word_limit:limit_words total="100"}
            text you want processed
        {/exp:word_limit}

        Note: The "total" parameter lets you specify the number of words.
        
        {exp:word_limit:limit_characters char="100"}
            text you want processed
        {/exp:word_limit}

        Note: The "char" parameter lets you specify the max number of characters. Will not break words.
        
        Version 1.2
        ******************
        GHC - Updated plugin to be 2.0 compatible

        <?php
        $buffer = ob_get_contents();

        ob_end_clean(); 

        return $buffer;
    }

    // --------------------------------------------------------------------

}
// END CLASS

/* End of file pi.word_limit.php */
/* Location: ./system/expressionengine/third_party/word_limit/pi.word_limit.php */