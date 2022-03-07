<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* ui functions
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Wscal_ui {

    // module name
    const MOD_NAME = 'wscal';
    
    public function __construct(){
    }
    
    /**
    * set right nav
    * @static
    * @return void
    */
    public function set_right_nav(){
        ee()->cp->set_right_nav(array(
                                    'Dash' => cp_url('addons_modules/show_module_cp', array('module' => $this::MOD_NAME)),  
                                    /*'Member Accounts' => cp_url('addons_modules/show_module_cp', array(
                                                                                                'module' => $this::MOD_NAME,
                                                                                                'method' => 'req',
                                                                                                'lib'    => 'wscal_members',
                                                                                                'libm'   => 'listing'
                                                                                                )),*/
                                    ));
    }
    
    /**
    * set right nav
    * @static
    * @return void
    */
    public function load_dash(){
        
        ee()->view->cp_page_title = '';

        // create dashboard links
        $vars = array(
                        /*'members' => cp_url('addons_modules/show_module_cp', array(
                                                                                    'module' => $this::MOD_NAME,
                                                                                    'method' => 'req',
                                                                                    'lib'    => 'wscal_members',
                                                                                    'libm'   => 'listing'
                                                                                    )),*/
                        
                        );

        // load dashboard view
        return ee()->load->view('admin/display/dashboard', $vars, TRUE);
    }

}