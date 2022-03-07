<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Neoseo_entry_rmdl extends Neoseo_rmdl_abstract {

    const DB_TABLE                      = 'exp_neoseo_entries';
    const DB_TABLE_PK                   = 'entry_id';
    const DB_TABLE_RECORD_CREATED_KEY   = 'created_at';
    const DB_TABLE_RECORD_MODIFIED_KEY  = 'updated_at';

    public function __construct() {
        parent::__construct();
    }
    
    public function get_ee_entry_title($entry_id){
        ee()->db->select('exp_channel_titles.title', FALSE);
        ee()->db->where('exp_channel_titles.entry_id', $entry_id);
        $query = ee()->db->get('exp_channel_titles');
        $result = $query->row();
        return $result->title;
    }
    
}