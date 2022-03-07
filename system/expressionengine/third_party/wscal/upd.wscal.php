<?php
if (!defined('BASEPATH'))
	exit ('No direct script access allowed');

/**
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 *
 */

class Wscal_upd {

	var $version = '1';
    const MOD_CLASS = 'Wscal';

	function __construct() {
    }

	/**
	 * Module Installer
	 * @access    public
	 * @return    bool
	 */
	function install() {

		ee()->load->dbforge();

		$data = array(
					'module_name'       => $this::MOD_CLASS,
					'module_version'    => $this->version,
					'has_cp_backend'    => 'y',
					'has_publish_fields' => 'n'
					);

		ee()->db->insert('modules', $data);

        $actions = array(
                        array(
                            'class'     => $this::MOD_CLASS,
                            'method'    => 'daily_cron'
                        )
                        );
        
        foreach($actions as $data){
            ee()->db->insert('actions', $data);
        }

		return TRUE;

	}

	/**
	 * Module Uninstaller
	 * @access public
	 * @return bool
	 */
	public function uninstall() {

        ee()->load->dbforge();
        ee()->db->select('module_id');
        $query = ee()->db->get_where('modules', array('module_name' => $this::MOD_CLASS));

        ee()->db->where('module_name', $this::MOD_CLASS);
        ee()->db->delete('modules');

        ee()->db->where('module_id', $query->row('module_id'));
        ee()->db->delete('module_member_groups');

        ee()->db->where('class', $this::MOD_CLASS);
        ee()->db->delete('actions');

        return TRUE;

	}

	/**
	 * Module Updater
	 * @access public
	 * @return bool
	 */
	public function update($current = '') {
		return TRUE;
	}
}