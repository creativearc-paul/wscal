<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 *
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 *
 */

class Neoseo_mcp {

	const MOD_NAME = 'neoseo';
   
    private $_abstracts = array(
                                'Neoseo_rmdl_abstract',
                                'Neoseo_vmdl_abstract',
                                'Neoseo_entity_abstract'
                                );

    // for loading module files
    private $_modFiles = array(
                                'models'    => array(),
                                'native_libraries' => array(
                                                    'pagination',
                                                    'table',
                                                    'api',
                                                    'email',
                                                    'file_field',
                                                    'form_validation'
						                            ),
                                'module_libraries' => array(
                                                    'form_helper',
                                                    'data_helper',
                                                    'ui',
                                                    'channel',
                                                    'entry'
                                                    ),
                                'helpers'   => array(
                                                    'date',
                                                    'form',
                                                    'security',
                                                    'html5_form_helper'
                                                    ),
                                'jqueryui'  => array(
                                                    'ui'        => array('core', 'datepicker', 'widget', 'mouse', 'slider', 'autocomplete', 'position', 'tabs', 'dialog', 'button', 'draggable', 'resizeable'),
                                                    'plugin'    => array('dataTables', 'overlay', 'wysihat', 'ee_filebrowser'),
                                                    'effects'   => array('fade')
                                                    )
                                );

	/**
	 * @access public
	 * @return void
	 */
	public function __construct() {

        ee()->config->load('config');
        // load abstract classes
        foreach($this->_abstracts as $abstract) {
            require_once(realpath(dirname(__FILE__)) . '/abstracts/' . $abstract . '.php');
        }

        // load module files
        ee()->lang->loadfile($this::MOD_NAME);
        ee()->cp->load_package_js($this::MOD_NAME);
        ee()->cp->load_package_css($this::MOD_NAME);
        
        foreach($this->_modFiles as $fileType=>$files) {
            if($fileType == 'jqueryui'){
                ee()->cp->add_js_script($files);
            }
            foreach($files as $file) {
                switch($fileType){
                    case 'helpers':
                        ee()->load->helper($file);
                        break;
                    case 'models':
                        ee()->load->model($file);
                        break;
                    case 'native_libraries':
                        ee()->load->library($file);
                        break;
                    case 'module_libraries':
                        ee()->load->library(ucfirst($this::MOD_NAME) . '_' . $file);
                        break;
                }
            }
        }
        
        // add RTE
        /*
        ee()->load->add_package_path(PATH_MOD.'rte/');
        ee()->load->library('rte_lib');
        ee()->javascript->output(
            ee()->rte_lib->build_js(0, '.rte', NULL, TRUE)
        );
        ee()->cp->add_to_head(ee()->view->head_link('css/rte.css'));
        */
        
        // check if Assets is installed
        if(array_key_exists('assets', ee()->addons->get_installed())){
            require_once PATH_THIRD.'assets/helper.php';
            $assets_helper = new Assets_helper;
            $assets_helper->include_sheet_resources();
        }
	
        // add right nav
        ee()->{$this::MOD_NAME . '_ui'}->set_right_nav();

		ee()->cp->set_breadcrumb(cp_url('addons_modules/show_module_cp', array('module' => $this::MOD_NAME)), ee()->lang->line($this::MOD_NAME . '_module_name '));       
        
	}

	/**
	 * DASHBOARD
	 * @access public
	 * @return string
	 * @author Greg Crane
	 */
	public function index() {
        /*
        $util = new Ghc_module_utility();

        $tables = array(
                        'exp_neoseo_channel',
                        'exp_neoseo_entry'
                        );

        foreach($tables as $table){
            echo '<br/><br/><pre>';
            echo print_r($util->construct_rules($table),TRUE);
            echo '</pre><br/><br/>';
        }
        die('dead');
        */
        
		// load dashboard view
        return ee()->{$this::MOD_NAME . '_ui'}->load_dash();
	}

	/**
	 * This is the main handler for requests. It loads the correct library and calls the correct method
	 * @access public
	 * @return string
	 * @author Greg Crane
	 */
	public function req() {

        try{

            if(!$library = ee()->input->get('lib', TRUE)){
                throw new Exception('Missing lib Param');
            }
            
            if(!$library_method = ee()->input->get('libm', TRUE)){
                throw new Exception('Missing libm Param');
            }

            // check for requested library, if not there, bail back to dashboard
            if(!ee()->$library){
                throw new Exception('Bad lib');
            }

            // now check that the rhandlered method exists, bail if not
            if(!method_exists(ee()->$library, $library_method)){
                throw new Exception('Bad libm');
            }

        } catch(Exception $e) {
            ee()->session->set_flashdata('message_failure', $e->getMessage());
            ee()->functions->redirect(cp_url('addons_modules/show_module_cp', array('module' => $this::MOD_NAME)));
            exit();
        }

        // it's all good, pass on the request
        return ee()->$library->$library_method();

	}

    /**
     * ajax datasource for listing tables
     * @access public
     * @return string
     * @author Greg Crane
     */
    public function listing_datasource($state, $params){

        try{
            // check that requested library is one of ours and if it's available to EE
            if(!in_array(str_replace($this::MOD_NAME . '_', '', $params['lib']), $this->_modFiles['module_libraries']) || !ee()->$params['lib']){
                throw new Exception('Bad Datasource.');
            }

        } catch(Exception $e) {
            ee()->session->set_flashdata('message_failure', $e->getMessage());
            ee()->functions->redirect(cp_url('addons_modules/show_module_cp', array('module' => $this::MOD_NAME)));
            exit();
        }

        // it's all good, pass on the request
        return ee()->$params['lib']->listing_datasource($state, $params);
    }

}