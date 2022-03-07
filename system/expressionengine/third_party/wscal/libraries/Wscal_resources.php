<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Wscal_resources{

    /**
    *  define some constants to make life easier
    */
    const LIB_CLASS     = 'wscal_resources';

    /**
    * @access public
    * @return void
    * @author Greg Crane
    */
    public function get_resource_years() {

        $years = array();

        ee()->db->select("FROM_UNIXTIME(field_id_81, '%Y') AS year", FALSE);
        ee()->db->distinct();
        ee()->db->where('channel_id', '5');
        ee()->db->order_by('year', 'desc');
        $query = ee()->db->get('exp_channel_data');

        if($query->num_rows() > 0){
            foreach($query->result() as $Row) {
                $years[] = array(
                    'year' => $Row->year
                );
            }
        }

        return $years;

    }
    
    /**
    * @access public
    * @return void
    * @author Greg Crane
    */
    public function get_resource_authors() {

        $years = array();

        ee()->db->select("FROM_UNIXTIME(field_id_81, '%Y') AS year", FALSE);
        ee()->db->distinct();
        ee()->db->where('channel_id', '5');
        ee()->db->order_by('year', 'desc');
        $query = ee()->db->get('exp_channel_data');

        if($query->num_rows() > 0){
            foreach($query->result() as $Row) {
                $years[] = array(
                    'year' => $Row->year
                );
            }
        }

        return $years;

    }
    
    /**
    * @access public
    * @return void
    * @author Greg Crane
    */
    public function get_resource_scriptures() {

        $years = array();

        ee()->db->select("FROM_UNIXTIME(field_id_81, '%Y') AS year", FALSE);
        ee()->db->distinct();
        ee()->db->where('channel_id', '5');
        ee()->db->order_by('year', 'desc');
        $query = ee()->db->get('exp_channel_data');

        if($query->num_rows() > 0){
            foreach($query->result() as $Row) {
                $years[] = array(
                    'year' => $Row->year
                );
            }
        }

        return $years;

    }

}