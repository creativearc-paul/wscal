<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 */

class Neoseo_form_helper{
    
    const MOD_NAME = 'neoseo';
                            
    /**
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function __construct() {
    }

    /**
     * @access public
     * @param string $field_name
     * @return array
     * @author Greg Crane
     */
    public function get_select_options($field_name) {
        
        $returnArray = array();
        
        switch($field_name){

            case 'channels': 
                ee()->load->model('view/Neoseo_channel_vmdl');
                $ViewModel = new Neoseo_channel_vmdl();
                return $ViewModel->get_form_options();
                break; 
                
            case 'twitter_card_type_ovid': 
                $ViewModel = $this->_get_option_view_model();
                return $ViewModel->get_form_options('twitter_card_type', FALSE);
                break;
                
            case 'open_graph_type_ovid': 
                $ViewModel = $this->_get_option_view_model();
                return $ViewModel->get_form_options('open_graph_type', FALSE);
                break;
                
            case 'meta_robots_ovid': 
                $ViewModel = $this->_get_option_view_model();
                return $ViewModel->get_form_options('meta_robots', FALSE);
                break;
            
            case 'no_yes': 
                $returnArray = array(
                                    '0'=>'No',
                                    '1'=>'Yes'
                                    );
                                    
            case 'no_yes_char': 
                $returnArray = array(
                                    'n'=>'No',
                                    'y'=>'Yes'
                                    );
                break;
                
            case 'channel_fields': 
                ee()->db->select('exp_channels.channel_id, exp_channels.field_group, exp_channel_fields.field_id, exp_channel_fields.field_label, exp_channel_fields.field_type');
                ee()->db->join('exp_channel_fields', 'exp_channels.field_group = exp_channel_fields.group_id', 'left');
                $query = ee()->db->get('exp_channels');

                $fields = $query->result();
                foreach($fields as $Field){
                    $returnArray[$Field->channel_id]['0'] = 'Entry Title';
                    if($Field->field_type == 'text'){
                        $returnArray[$Field->channel_id][$Field->field_id] = $Field->field_label;
                    }
                }
                break;        


            default: break;
        }
        
        return $returnArray;
        
    }
    
    /**
     * @access public
     * @param string $field_name
     * @return array
     * @author Greg Crane
     */
    public function get_checkboxes($field_name) {
    
        $returnArray = array();
        
        switch($field_name){
            default: break;
        }
                    
        return $returnArray;   
                
    }
    
    /**
     * @access pricate
     * @param string $field_name
     * @return array
     * @author Greg Crane
     */
    private function _get_option_view_model() {
    
        ee()->load->model('view/' . $this::MOD_NAME . '_option_value_vmdl');
        $vmdl = $this::MOD_NAME . '_option_value_vmdl'; 
        $ViewModel = new $vmdl();
                    
        return $ViewModel;   
                
    }
    
}