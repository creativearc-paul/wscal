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
class Neoseo {

	const MOD_NAME = 'neoseo';
    
	private $_returnData = '';

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
                                'helpers'   => array('text','date','form','security','url','html5_form_helper'),

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
        foreach($this->_modFiles as $fileType=>$files) {
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

	}

    /**
     * get description
     * @access public
     * @return string
     * @author Greg Crane
     */
    public function meta_tags(){

        try{
            if(!$entry_id = ee()->TMPL->fetch_param('entry_id')){
                throw new Exception('<!-- missing entry id -->');
            }
            $tags = (ee()->TMPL->fetch_param('tags')) ? ee()->TMPL->fetch_param('tags') : NULL;
            return ee()->{$this::MOD_NAME . '_entry'}->meta_tags($entry_id, $tags);
            
        } catch(Exception $e) {
            return $e->getMessage();
        }

    }

}