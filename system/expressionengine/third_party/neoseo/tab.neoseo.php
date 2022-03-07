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
class Neoseo_tab {

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

	function __construct() {

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

	}

    
    /**
     * publish tabs
     * @access public
     * @param int $channel_id
     * @param int $entry_id
     * @return array
     * @author Greg Crane
     */
    public function publish_tabs($channel_id, $entry_id = 0){
        // bail if seo not managed for channel
        ee()->load->model('record/Neoseo_channel_rmdl');
        $RecordModel = new Neoseo_channel_rmdl();
        $RecordModel->load((int)$channel_id);
        if($RecordModel->manage_channel === 'n'){
            return array();
        }

        return ee()->neoseo_entry->publish_tabs($channel_id, $entry_id);
    }

     /**
     * validate publish tabs
     * @access public
     * @param $params
     * @return bool
     * @author Greg Crane
     */
    public function validate_publish($params){
        // bail if seo not managed for channel
        ee()->load->model('record/Neoseo_channel_rmdl');
        $RecordModel = new Neoseo_channel_rmdl();
        $RecordModel->load((int)$params[0]['channel_id']);
        if($RecordModel->manage_channel === 'n'){
            return;
        }
        return;
    }

    /**
     * save data
     * @access public
     * @param array $entries
     * @return void
     * @author Greg Crane
     */
    public function publish_data_db($entries){

        // bail if seo not managed for channel
        ee()->load->model('record/Neoseo_channel_rmdl');
        $RecordModel = new Neoseo_channel_rmdl();
        $RecordModel->load((int)$entries['meta']['channel_id']);
        if($RecordModel->manage_channel === 'n'){
            return;
        }
        return ee()->neoseo_entry->publish_data_db($entries);
    }

    /**
     * delete data
     * @access public
     * @param array $entries
     * @return void
     * @author Greg Crane
     */
    public function publish_data_delete_db($entries){
        // load model
        ee()->load->model('record/Neoseo_entry_rmdl');

        foreach($entries['entry_ids'] as $key=>$id){
            $RecordModel = new Neoseo_entry_rmdl();
            $RecordModel->load($id);
            $RecordModel->delete();
            unset($RecordModel);
        }
    }


}