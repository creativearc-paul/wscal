<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Wscal_ext {

	public $description     = 'Wscal utiities';
	public $docs_url        = 'http://creativearc.com/';
	public $name            = 'Wscal utilities';
	public $settings_exist  = 'n';
	public $version         = '1';

	/**
	 * Constructor
	 * @access public
	 * @return void
	 */
	public function __construct($settings = '') {
	}

	/**
	 * activate extension
	 * @access public
	 * @return void
	 */
	public function activate_extension() {

        ee()->db->insert('extensions', array(
                                            'class'    => __CLASS__,
                                            'method'   => 'member_delete',
                                            'hook'     => 'member_delete',
                                            'settings' => '',
                                            'priority' => 10,
                                            'version'  => $this->version,
                                            'enabled'  => 'y'
                                            ));
        ee()->db->insert('extensions', array(
                                            'class'    => __CLASS__,
                                            'method'   => 'member_create_end',
                                            'hook'     => 'member_create_end',
                                            'settings' => '',
                                            'priority' => 10,
                                            'version'  => $this->version,
                                            'enabled'  => 'y'
                                            ));
        ee()->db->insert('extensions', array(
                                            'class'    => __CLASS__,
                                            'method'   => 'delete_entries_loop',
                                            'hook'     => 'delete_entries_loop',
                                            'settings' => '',
                                            'priority' => 10,
                                            'version'  => $this->version,
                                            'enabled'  => 'y'
                                            ));
	}
   
    /**
     *  create member 
     * 
     */
    public function member_create_end($member_id, $data, $cdata) {

        return TRUE;
    }
    
    /**
     * member delete 
     * 
     */
    public function member_delete($member_ids) {

    }

    /**
     * entry delete 
     * 
     */
    public function delete_entries_loop($entry_id, $channel_id){

    }
    
	/**
	 * disable extension
	 * @access public
	 * @return void
	 */
	public function disable_extension() {
		ee()->db->where('class', __CLASS__)->delete('extensions');
	}

	/**
	 * update extension
	 * @access public
	 * @param string
	 * @return bool
	 */
	public function update_extension($current = '') {
		if ($current == '' OR $current == $this->version) {
			return FALSE;
		}
	}

}