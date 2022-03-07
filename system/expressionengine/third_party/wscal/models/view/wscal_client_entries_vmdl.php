<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Wscal_client_entries_vmdl extends Wscal_vmdl_abstract {

    const DB_TABLE      = 'exp_wscal_client_entries';
    const DB_TABLE_PK   = 'exp_wscal_client_entries.id';

    public $_columnMap = array(
                                'id'                    => 'exp_wscal_client_entries.id',
                                'created_at'            => 'exp_wscal_client_entries.created_at',
                                'updated_at'            => 'exp_wscal_client_entries.updated_at',
                                'display_created_at'    => 'IF(exp_wscal_client_entries.created_at, date_format(exp_wscal_client_entries.created_at, "%c-%e-%Y, %l:%i %p"), NULL)',
                                'display_updated_at'    => 'IF(exp_wscal_client_entries.updated_at, date_format(exp_wscal_client_entries.updated_at, "%c-%e-%Y, %l:%i %p"), NULL)',
                                'member_id'             => 'exp_wscal_client_entries.member_id',
                                'entry_id'              => 'exp_wscal_client_entries.entry_id',
                                'field_id_5'            => 'exp_channel_data.field_id_5',
                                'title'                 => 'exp_channel_titles.title',
                                'username'              => 'exp_members.username',
                                'screen_name'           => 'exp_members.screen_name'
                                );

    public $_join = array(
                        'exp_channel_titles' => 'exp_wscal_client_entries.entry_id = exp_channel_titles.entry_id',
                        'exp_channel_data' => 'exp_wscal_client_entries.entry_id = exp_channel_data.entry_id',
                        'exp_members' => 'exp_wscal_client_entries.member_id = exp_members.member_id',
                        );
                                
    public $_date_columns = array(
                                'created_at' => array( 
                                                    'column' => 'exp_wscal_client_entries.created_at',
                                                    'format' => 'mysql_datetime'
                                                    ),
                                'updated_at' => array( 
                                                    'column' => 'exp_wscal_client_entries.updated_at',
                                                    'format' => 'mysql_datetime'
                                                    )
                                );
                                
    public function __construct() {
    }
    
    /**
    * get categories for entry
    * @return array
    */
    public function get_entry_categories($entry_id = 0) {
        $categories = array();
        $this->db->select('exp_category_posts.*,exp_categories.cat_url_title,exp_categories.cat_name');
        $this->db->join('exp_categories', 'exp_category_posts.cat_id = exp_categories.cat_id', 'left');
        $query = $this->db->get_where('exp_category_posts', array('entry_id' => $entry_id));
        if($query->num_rows() > 0){
            $results = $query->result();
            foreach($results as $Row){
                $categories[$Row->cat_url_title] = $Row->cat_name;
            }
        }
        return $categories;
    }
    
    /**
    * get entries for assignment
    * @return mixed
    */
    public function get_entries() {
        $this->db->select('exp_channel_titles.entry_id, exp_channel_titles.title, exp_channel_data.field_id_5');
        $this->db->join('exp_channel_data', 'exp_channel_titles.entry_id = exp_channel_data.entry_id', 'left');
        $query = $this->db->get_where('exp_channel_titles',array('exp_channel_titles.channel_id'=>2));
        if($query->num_rows() > 0){
            $result = $query->result();
            return $result;
        }
        return FALSE;
    }

}
