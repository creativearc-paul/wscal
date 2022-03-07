<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 */

class Wscal_form_helper{
                            
    /**
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function __construct() {
    }

    /**
     * @access public
     * @param string $field
     * @return array
     * @author Greg Crane
     */
    public function get_select_options($field) {
        
        $returnArray = array();
        
        switch($field){

            case 'members': 
                ee()->load->model('view/Wscal_members_vmdl');
                $ViewModel = new Wscal_members_vmdl();
                $result = $ViewModel->get_form_options();
                $returnArray[''] = '';
                foreach($result as $Row) {
                    $returnArray[$Row->member_id] = $Row->username;
                }
                break;
                
            case 'member_groups': 
                $returnArray = array(
                                    '' => 'All',
                                    '5' => 'Members'
                                    );
                
                break;
                
            default: break;
        }
        
        return $returnArray;
        
    }
    
    /**
     * @access public
     * @param string $field
     * @return array
     * @author Greg Crane
     */
    public function get_checkboxes($field) {
    
        $returnArray = array();
        
        return $returnArray;   
                
    }
    
}