<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Neoseo_option_value_vmdl extends Neoseo_vmdl_abstract {

    const DB_TABLE      = 'exp_neoseo_option_values';
    const DB_TABLE_PK   = 'exp_neoseo_option_values.option_value_id';
                                
    public function __construct() {
        
        parent::__construct();
        
        $this->_columnMap = array(
                                'option_value_id'       => $this::OPTION_VALUES_TABLE . '.option_value_id',
                                'created_at'            => $this::OPTION_VALUES_TABLE . '.created_at',
                                'updated_at'            => $this::OPTION_VALUES_TABLE . '.updated_at',
                                'display_created_at'    => 'IF(' . $this::OPTION_VALUES_TABLE . '.created_at, date_format(' . $this::OPTION_VALUES_TABLE . '.created_at, "%c-%e-%Y, %l:%i %p"), NULL)',
                                'display_updated_at'    => 'IF(' . $this::OPTION_VALUES_TABLE . '.updated_at, date_format(' . $this::OPTION_VALUES_TABLE . '.updated_at, "%c-%e-%Y, %l:%i %p"), NULL)',
                                'option_group'          => $this::OPTION_VALUES_TABLE . '.option_group',
                                'option_value'          => $this::OPTION_VALUES_TABLE . '.option_value',
                                'option_label'          => $this::OPTION_VALUES_TABLE . '.option_label'
                                );

        $this->_date_columns = array(
                                'created_at' => array( 
                                                    'column' => $this::OPTION_VALUES_TABLE . '.created_at',
                                                    'format' => 'mysql_datetime'
                                                    ),
                                'updated_at' => array( 
                                                    'column' => $this::OPTION_VALUES_TABLE . '.updated_at',
                                                    'format' => 'mysql_datetime'
                                                    )
                                );
    }
    
    /**
    * get form options
    * @access public
    * @return array
    */
    public function get_form_options($option_group, $include_blank = TRUE){
        $this->db->select('option_value_id, option_label');
        $this->db->where('option_group',$option_group);
        $this->db->order_by('sort_order','asc');
        $query = $this->db->get($this::OPTION_VALUES_TABLE);
        if($query->num_rows() > 0){
            if($include_blank){
                $returnArray = array(''=>'');
            }
            foreach($query->result() as $Row) {
                $returnArray[$Row->option_value_id] =  $Row->option_label;
            }
            return $returnArray;
        }
        return array(''=>'');
    }
    
}
