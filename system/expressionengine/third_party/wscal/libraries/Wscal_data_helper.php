<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 */

class Wscal_data_helper{
                            
    /**
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function __construct() {
    }
   
    /**
     * generate a random alpha numeric string
     * @param int $length
     * @access public
     * @return string
     */
    public function generate_password($length = 32) {
        $chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        $pass = '';
        for($i = 0; $i < $length; $i++) {
            $pass .= $chars[mt_rand(0, count($chars) - 1)];
        }
        return $pass;
    }
    
}