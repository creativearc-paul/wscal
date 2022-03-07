<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Wscal_blog{

    /**
    *  define some constants to make life easier
    */
    const LIB_CLASS     = 'wscal_blog';

    /**
    * @access public
    * @return void
    * @author Greg Crane
    */
    public function get_blog_archive_years() {

        $years = array();

        ee()->db->select('year');
        ee()->db->distinct();
        ee()->db->where('channel_id', '3');
        ee()->db->order_by('year', 'desc');
        $query = ee()->db->get('exp_channel_titles');

        if($query->num_rows() > 0){
            foreach($query->result() as $Row)   {
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
    public function get_blog_contributors() {

        $contributors = array();
        ee()->db->select('field_id_41 AS contributor');
        ee()->db->distinct();
        ee()->db->where('channel_id', '3');
        ee()->db->where('field_id_41 !=', '-');
        $query = ee()->db->get('exp_channel_data');

        if($query->num_rows() > 0){
            foreach($query->result() as $Row)   {
                $contributors[] = array(
                    'contributor_link' => urlencode($Row->contributor),
                    'contributor_name' => $Row->contributor
                );
            }
        }
        
        return $contributors;

    }

}