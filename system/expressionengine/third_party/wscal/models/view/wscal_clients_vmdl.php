<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Wscal_clients_vmdl extends Wscal_vmdl_abstract {

    const DB_TABLE      = 'exp_wscal_clients';
    const DB_TABLE_PK   = 'exp_wscal_clients.id';

    public $_columnMap = array(
                                'id'                    => 'exp_wscal_clients.id',
                                'created_at'            => 'exp_wscal_clients.created_at',
                                'updated_at'            => 'exp_wscal_clients.updated_at',
                                'display_created_at'    => 'IF(exp_wscal_clients.created_at, date_format(exp_wscal_clients.created_at, "%c-%e-%Y, %l:%i %p"), NULL)',
                                'display_updated_at'    => 'IF(exp_wscal_clients.updated_at, date_format(exp_wscal_clients.updated_at, "%c-%e-%Y, %l:%i %p"), NULL)',
                                'member_id'             => 'exp_wscal_clients.member_id',
                                'permission_entry_id'   => 'exp_wscal_clients.permission_entry_id',
                                'title'                 => 'exp_channel_titles.title',
                                'url_title'             => 'exp_channel_titles.url_title',
                                'expiration_date'       => 'exp_wscal_clients.expiration_date',
                                'display_expiration_date' => 'IF(exp_wscal_clients.expiration_date, date_format(exp_wscal_clients.expiration_date, "%c-%e-%Y"), NULL)',
                                );

    public $_join = array(
                        'exp_channel_titles' => 'exp_wscal_clients.permission_entry_id = exp_channel_titles.entry_id'
                        );
                                
    public $_date_columns = array(
                                'created_at' => array( 
                                                    'column' => 'exp_wscal_clients.created_at',
                                                    'format' => 'mysql_datetime'
                                                    ),
                                'updated_at' => array( 
                                                    'column' => 'exp_wscal_clients.updated_at',
                                                    'format' => 'mysql_datetime'
                                                    ),
                                'expiration_date' => array( 
                                                    'column' => 'exp_wscal_clients.expiration_date',
                                                    'format' => 'mysql_date'
                                                    )
                                );
                                
    public function __construct() {
    }
    
    /**
    * get accounts to expire
    * @access public
    * @return array
    */
    public function expire_accounts(){
        
        // anything before today and in Members group
        $today = date('Y-m-d', strtotime('-1 days'));
        $where = 'exp_wscal_clients.expiration_date <= "' . $today . '" AND exp_members.group_id = 5';
        
        $this->db->select('exp_members.username,exp_members.member_id');
        $this->db->join('exp_members', 'exp_wscal_clients.member_id = exp_members.member_id', 'left');
        $this->db->where($where);
        $query = $this->db->get('exp_wscal_clients');
        $results = $query->result();
        
        $memberIds = array(); // members to expire
        $usernames = ''; // usernames to send in notification
        
        foreach($results as $Member){
            $memberIds[] = $Member->member_id;
            $usernames .= $Member->username . ' (ID: ' . $Member->member_id . ')<br>';
        }
        
        if(count($memberIds) > 0){
            // change member group to "Expired"
            $data = array(
                        'group_id' => 7
                        );
            $this->db->where_in('member_id', $memberIds);
            $this->db->update('exp_members', $data);
            
            return $usernames;
        } else {
            return 'No members expired.';
        }
        
         
    }
    
}
