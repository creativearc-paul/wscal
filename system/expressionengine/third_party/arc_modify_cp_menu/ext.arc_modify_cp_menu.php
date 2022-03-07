<?php
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    /**
    * Modify Control Panel Menu Extension
    *
    * @package        ExpressionEngine
    * @subpackage    Addons
    * @category    Extension
    * @author        Greg Crane
    * @link        http://creativearc.com/
    */

    class Arc_modify_cp_menu_ext {

        public $settings = array();
        public $description = 'Modifies Control Panel Menu';
        public $docs_url = 'http://creativearc.com/';
        public $name = 'Modify Control Panel Menu';
        public $settings_exist = 'n';
        public $version = '1.0';

        private $EE;

    /**
     * Constructor
     * 
     * @access public
     * @return void
     */
    public function __construct($settings = '') {
        $this->settings = $settings;
    }

    /**
     * activate extension
     * 
     * @access public
     * @return void
     */
    public function activate_extension() {
        $this->settings = array();

            $data = array(
                'class'     => __CLASS__,
                'method'    => 'build_custom_menu',
                'hook'      => 'cp_menu_array',
                'settings'  => serialize($this->settings),
                'version'   => $this->version,
                'enabled'   => 'y'
            );

            ee()->db->insert('extensions', $data);
        }

    /**
     * modify menu
     * 
     * @access public
     * @param array $menu
     * @return array
     */
    public function build_custom_menu($menu){
        
        if(ee()->extensions->last_call !== FALSE){
            $menu = ee()->extensions->last_call;
        }
        
        $assigned = ee()->session->userdata('assigned_modules');

        // reverse menu so first is last
        $menu = array_reverse($menu, true); 
        
        // add structure
        ee()->db->select('module_id')->from('modules')->where('module_name', "Structure")->limit(1);
        $query = ee()->db->get();
        if($query->num_rows() > 0){
            $module_id = $query->row('module_id');
            $is_assigned = ee()->session->userdata('assigned_modules');
            if(ee()->cp->allowed_group('can_access_modules') && (ee()->session->userdata('group_id') == 1 || (isset($is_assigned[$module_id]) && $assigned[$module_id] == 'yes'))){
                $menu['structure'] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=structure'; 
                ee()->lang->language['nav_structure'] = 'Structure';
            }
        }

        // reverse menu so last is first again
        $menu = array_reverse($menu, true); 
      
        // add freeform
        ee()->db->select('module_id')->from('modules')->where('module_name', "Freeform")->limit(1);
        $query = ee()->db->get();
        if($query->num_rows() > 0){
            $module_id = $query->row('module_id');
            $is_assigned = ee()->session->userdata('assigned_modules');
            if(ee()->cp->allowed_group('can_access_modules') && (ee()->session->userdata('group_id') == 1 || (isset($is_assigned[$module_id]) && $assigned[$module_id] == 'yes'))){
                $menu['freeform'] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=freeform'; 
                ee()->lang->language['nav_freeform'] = 'Form Submissions';
            }
        }
        
        return $menu;
    }

    /**
     * disable extension
     * 
     * @access public
     * @return void
     */
    public function disable_extension() {
        ee()->db->where('class', __CLASS__);
        ee()->db->delete('extensions');
    }

    /**
     * update extension
     * 
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
