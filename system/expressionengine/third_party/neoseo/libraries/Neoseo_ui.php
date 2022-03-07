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

class Neoseo_ui {

    // module name
    const MOD_NAME = 'neoseo';
    
    public function __construct(){
    }
    
    /**
    * set right nav
    * @static
    * @return void
    */
    public function set_right_nav(){
        ee()->cp->set_right_nav(array(
                                    'Dash' => BASE . AMP . 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=' . $this::MOD_NAME,  
                                    'Entry Metadata' => BASE . AMP . 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=' . $this::MOD_NAME . AMP . 'method=req' . AMP . 'lib=neoseo_entry' . AMP . 'libm=listing',
                                    'Channel Settings / Defaults' => BASE . AMP . 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=' . $this::MOD_NAME . AMP . 'method=req' . AMP . 'lib=neoseo_channel' . AMP . 'libm=listing',
                                    ));
    }
    
    /**
    * set right nav
    * @static
    * @return void
    */
    public function load_dash(){
        
        ee()->view->cp_page_title = 'NeoSEO';

        // create dashboard links
        $vars = array(
                        'entries' => BASE . AMP . 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=' . $this::MOD_NAME . AMP . 'method=req' . AMP . 'lib=neoseo_entry' . AMP . 'libm=listing',
                        'channels' => BASE . AMP . 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=' . $this::MOD_NAME . AMP . 'method=req' . AMP . 'lib=neoseo_channel' . AMP . 'libm=listing',
                        );

        // load dashboard view
        return ee()->load->view('admin/display/dashboard', $vars, TRUE);
    }

}