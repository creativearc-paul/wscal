<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Wscal_news_events{

    /**
    *  define some constants to make life easier
    */
    const LIB_CLASS     = 'wscal_news_events';

    /**
    * @access public
    * @return void
    * @author Greg Crane
    */
    public function get_news_events_archive_years() {

        $years = array();

        ee()->db->select('year');
        ee()->db->distinct();
        ee()->db->where('channel_id', '10');
        ee()->db->where('year >= ', '2017');
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
    

}
