<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 */

class Neoseo_analyzer{

    /**
    *  define some constants to make life easier
    */
    const LIB_CLASS     = 'neoseo_analyzer';
    const DISPLAY_NAME  = 'Analyzer';


    /**
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function __construct() {
      
    }

    /**
     * get links from a url
     * @param string $url
     * @access public
     * @return array
     */
    public function get_links($url) { 

        $domSrc = file_get_contents($url);
        
        $domDoc = new DOMDocument();
        @$domDoc->loadHTML($domSrc); 

        $links = array(); 
        foreach($domDoc->getElementsByTagName('a') as $link) { 
            $links[] = array('url' => $link->getAttribute('href'), 'text' => $link->nodeValue); 
        } 

        return $links;
    } 
    
    /**
     * get images from a url
     * @param string $url
     * @access public
     * @return array
     */
    public function get_images($url) { 

        $domSrc = file_get_contents($url);
        
        $domDoc = new DOMDocument();
        @$domDoc->loadHTML($domSrc); 

        $images = array(); 
        foreach($domDoc->getElementsByTagName('img') as $image) { 
            $images[] = array('src' => $image->getAttribute('src'), 'alt' => $image->getAttribute('alt')); 
        } 

        return $images; 
    }
    
}