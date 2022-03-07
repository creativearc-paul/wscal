<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Wscal_members_vmdl extends Wscal_vmdl_abstract {

    const DB_TABLE      = 'exp_members';
    const DB_TABLE_PK   = 'exp_members.member_id';

    public $_columnMap = array(
                                'group_id'                  => 'exp_members.group_id',
                                'group_title'               => 'exp_member_groups.group_title',
                                'join_date'                 => 'exp_members.join_date',
                                'display_join_date'         => 'FROM_UNIXTIME(exp_members.join_date, "%c/%e/%Y, %l:%i %p")',
                                'last_visit'                => 'exp_members.last_visit',
                                'display_last_visit'        => 'FROM_UNIXTIME(exp_members.last_visit, "%c/%e/%Y, %l:%i %p")',
                                'last_activity'             => 'exp_members.last_activity',
                                'display_last_activity'     => 'FROM_UNIXTIME(exp_members.last_activity, "%c/%e/%Y, %l:%i %p")',
                                'member_id'                 => 'exp_members.member_id',
                                'email'                     => 'exp_members.email',
                                'username'                  => 'exp_members.username',
                                'screen_name'               => 'exp_members.screen_name',
                                'id'                        => 'exp_wscal_clients.id',
                                'permission_entry_id'       => 'exp_wscal_clients.permission_entry_id',
                                'title'                     => 'exp_channel_titles.title',
                                'url_title'                 => 'exp_channel_titles.url_title',
                                'expiration_date'           => 'exp_wscal_clients.expiration_date',
                                'display_expiration_date'   => 'IF(exp_wscal_clients.expiration_date, date_format(exp_wscal_clients.expiration_date, "%c/%e/%Y"), NULL)',
                                );
    public $_join = array(
                        'exp_member_data' => 'exp_members.member_id = exp_member_data.member_id',
                        'exp_wscal_clients' => 'exp_members.member_id = exp_wscal_clients.member_id',
                        'exp_channel_titles' => 'exp_wscal_clients.permission_entry_id = exp_channel_titles.entry_id',
                        'exp_member_groups' => 'exp_members.group_id = exp_member_groups.group_id',
                        );
    
    public $_date_columns = array(
                                'join_date' => array( 
                                                    'column' => 'exp_members.join_date',
                                                    'format' => 'unixtime'
                                                    ),
                                'last_visit' => array( 
                                                    'column' => 'exp_members.last_visit',
                                                    'format' => 'unixtime'
                                                    ),
                                'last_activity' => array( 
                                                    'column' => 'exp_members.last_activity',
                                                    'format' => 'unixtime'
                                                    ),
                                'expiration_date' => array( 
                                                    'column' => 'exp_wscal_clients.expiration_date',
                                                    'format' => 'mysql_date'
                                                    )
                                );
    /**
    * allows for a where condition to always apply
    * @var array
    */
    public $_always_where_in = array(
                                'group_id' => array(5,6,7)
                                );
    
    /**
    * always select these fields, mostly needed if using modifiying results function
    * $_always_select = array(
    *                       'group_id',
    *                       'group_name'
    *                       );
    * @var array
    */
    public $_always_select = array(
                                'member_id'
                                );
    
    public function __construct() {
    }
    
    /**
    * modify results if required
    * @access public
    * @param array $results_array
    * @return array
    */
    public function get_related($results_array){
        
        return $results_array;
        
    }
    
    /**
    * get form options
    * @access public
    * @return array
    */
    public function get_form_options(){
        $this->db->select('exp_members.member_id, exp_members.username', FALSE);
        $this->db->join('exp_member_data','exp_member_data.member_id = exp_members.member_id');
        $this->db->where('exp_members.group_id', 5);
        $this->db->order_by('exp_members.username', 'asc');
        $query = $this->db->get('exp_members');
        return $query->result();
    }
}