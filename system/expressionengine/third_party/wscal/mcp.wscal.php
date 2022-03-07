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

class Wscal_mcp {

	const MOD_NAME = 'wscal';
   
    private $_abstracts = array(
                                'Wscal_rmdl_abstract',
                                'Wscal_vmdl_abstract',
                                'Wscal_entity_abstract'
                                );

    // for loading module files
    private $_modFiles = array(
                                'models'    => array(),
                                'libraries' => array(
                                                    'pagination',
                                                    'table',
                                                    'api',
                                                    'email',
                                                    'form_validation',
                                                    'Wscal_data_helper',
                                                    'Wscal_form_helper',
                                                    'Wscal_ui',
                                                    'Wscal_members'
                                                    ),
                                'css'       => array(
                                                    'wscal'
                                                    ),
                                'javascript'=> array(
                                                    'wscal'
                                                    ),
                                'helpers'   => array('text','date','form','security','url','html5_form_helper'),
                                'lang'      => array('wscal'),
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
        foreach($this->_modFiles as $fileType=>$files) {
            if($fileType == 'jqueryui'){
                ee()->cp->add_js_script($files);
            }
            foreach($files as $file) {
                switch($fileType){
                    case 'helpers':
                        ee()->load->helper($file);
                        break;
                    case 'lang':
                        ee()->lang->loadfile($file);
                        break;
                    case 'javascript':
                        ee()->cp->load_package_js($file);
                        break;
                    case 'css':
                        ee()->cp->load_package_css($file);
                        break;
                    case 'models':
                        ee()->load->model($file);
                        break;
                    case 'libraries':
                        ee()->load->library($file);
                        break;
                }
            }
        }
        
        // add rte
        ee()->load->add_package_path(PATH_MOD.'rte');
        ee()->load->library('rte_lib');
        ee()->javascript->output(
                                ee()->rte_lib->build_js(0, '.rte', NULL, TRUE)
                                );
        
        // add rte css
        ee()->cp->add_to_head(ee()->view->head_link('css/rte.css'));
        
        // add right nav
        ee()->wscal_ui->set_right_nav();

		ee()->cp->set_breadcrumb(cp_url('addons_modules/show_module_cp', array('module' => $this::MOD_NAME)), ee()->lang->line('wscal_module_name'));

	}

	/**
	 * DASHBOARD
	 * @access public
	 * @return string
	 * @author Greg Crane
	 */
	public function index(){

        die('done');
        
/*        $query = ee()->db->query('SELECT
                    exp_freeform_forms.form_id,
                    exp_freeform_forms.form_name,
                    exp_freeform_forms.form_label
                    FROM
                    exp_freeform_forms
                    WHERE
                    exp_freeform_forms.form_id NOT IN (2,3)');

        $results = $query->result();

        foreach($results as $Row){

            $data = array(
                    'site_id'=>1,
                    'notification_name'=>$Row->form_name,
                    'notification_label'=>$Row->form_label,
                    'notification_description'=>'',
                    'wordwrap'=>'y',
                    'allow_html'=>'y',
                    'from_name'=>'{first_name} {last_name}',
                    'from_email'=>'{email}',
                    'reply_to_email'=>'{email}',
                    'email_subject'=>'Wscal.edu '.$Row->form_label.' form',
                    'include_attachments'=>'n',
                    'template_data'=>'<table cellpadding="5" cellspacing="0" style="width:800px;">
                                        {all_form_fields}
                                             <tr>
                                                <td style="vertical-align:top;font-weight:bold;width:150px;">{field_label}</td>
                                                <td style="vertical-align:top;">{field_data}</td>
                                             </tr>
                                        {/all_form_fields}
                                        </table>'
                           );
            
            ee()->db->insert('exp_freeform_notification_templates', $data);
            $id = ee()->db->insert_id();
            
            $ndata = array(
               'admin_notification_id' => $id
            );

            ee()->db->where('form_id', $Row->form_id);
            ee()->db->update('exp_freeform_forms', $ndata); 
            
        }
        
       */
        
        
            // CREATE TABLES
/*        ee()->load->dbforge();
        $forms = array();
        $query = ee()->db->query('SELECT
                exp_freeform_forms.form_id,
                exp_freeform_forms.form_name,
                exp_freeform_fields.field_id,
                ft_form_fields.field_name
                FROM
                exp_freeform_forms
                LEFT JOIN ft_form_fields ON exp_freeform_forms.form_description = ft_form_fields.form_id
                LEFT JOIN exp_freeform_fields ON ft_form_fields.field_name = exp_freeform_fields.field_name
                WHERE
                exp_freeform_fields.field_id IS NOT NULL
                ORDER BY
                exp_freeform_forms.form_id ASC,
                ft_form_fields.list_order ASC');

        $results = $query->result();

        foreach($results as $Row){

            if(!array_key_exists($Row->form_id, $forms)){
                $forms[$Row->form_id][] = $Row->field_id;
            } else {
                $forms[$Row->form_id][] .= $Row->field_id;
            }

            //ee()->db->insert('exp_freeform_forms', $data);

        }

        foreach($forms as $form_id => $field_ids){
            foreach($field_ids as $field_id){
                ee()->dbforge->add_column(
                    'freeform_form_entries_' . $form_id,
                    array(
                        'form_field_' . $field_id => array(
                            'type' => 'TEXT',
                        ),
                    )
                );
            }
            //ee()->db->insert('exp_freeform_forms', $data);
            $data = array(
               'field_ids' => implode('|',$field_ids),
               'field_order' => implode('|',$field_ids)
            );

            ee()->db->where('form_id', $form_id);
            ee()->db->update('exp_freeform_forms', $data); 
        }

        
        echo '<br/><br/>LINE: ' . __LINE__ . '<br/><pre>';
        echo print_r($forms,TRUE);
        echo '</pre><br/><br/>';
        die('dead');*/
        
        
        /*        	    
        // CREATE TABLES
        $query = ee()->db->query('SELECT 
                        * 
                        FROM
                        ft_forms');

        $results = $query->result();
        
        foreach($results as $Row){

            $data = array(
                'site_id'                  => 1,
                'form_name'                => str_replace(' ','_',strtolower(preg_replace("/[^A-Za-z ]/", '', $Row->form_name))),
                'form_label'               => $Row->form_name,
                'default_status'           => 'pending',
                'notify_user'              => 'n',
                'notify_admin'             => 'y',
                'user_email_field'         => '',
                'user_notification_id'     => 0,
                'admin_notification_id'    => 1,
                'admin_notification_email' => 'greg@creativearc.com',
                'form_description'         => $Row->form_id,
                'field_ids'                => '',
                'field_order'              => '',
                'template_id'              => 0,
                'composer_id'              => 0,
                'author_id'                => 1,
                'entry_date'               => strtotime($Row->date_created),
                'edit_date'                => strtotime('now'),
                'settings'                 => '',
            );
            
            ee()->db->insert('exp_freeform_forms', $data);
            $id = ee()->db->insert_id();

            ee()->db->query("CREATE TABLE `exp_freeform_form_entries_".$id."` (
                              `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                              `site_id` int(10) unsigned NOT NULL DEFAULT '1',
                              `author_id` int(10) unsigned NOT NULL DEFAULT '0',
                              `complete` varchar(1) NOT NULL DEFAULT 'y',
                              `ip_address` varchar(40) NOT NULL DEFAULT '0',
                              `entry_date` int(10) unsigned NOT NULL DEFAULT '0',
                              `edit_date` int(10) unsigned NOT NULL DEFAULT '0',
                              `status` varchar(50) DEFAULT NULL,
                              PRIMARY KEY (`entry_id`)
                            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

        }
        */

        // CREATE FIELDS
/*        $query = ee()->db->query("SELECT
                                                ft_form_fields.field_id,
                                                ft_form_fields.form_id,
                                                ft_form_fields.field_name,
                                                ft_form_fields.field_test_value,
                                                ft_form_fields.field_size,
                                                ft_form_fields.field_type,
                                                ft_form_fields.data_type,
                                                ft_form_fields.field_title,
                                                ft_form_fields.col_name,
                                                ft_form_fields.list_order,
                                                ft_form_fields.include_on_redirect,
                                                ft_form_fields.field_group_id
                                    FROM
                                                ft_form_fields
                                    WHERE
                                    ft_form_fields.field_type <> 'system' AND
                                    ft_form_fields.field_name <> 'submit'
                                    ORDER BY
                                    ft_form_fields.field_name ASC");

        $results = $query->result();

        $existing = array('anticipated_starting_term',
                            'book_author',
                            'degree_program_of_interest',
                            'email',
                            'first_name',
                            'isbn',
                            'last_name',
                            'name',
                            'oclc_number',
                            'phone_number',
                            'publication_date',
                            'publisher',
                            'special_instructions',
                            'title_of_book',
                            'your_status');
        
        $fields = array();
        
        foreach($results as $Row){

            if(!in_array($Row->field_name, $existing)){
                $fields[$Row->field_name] = array(
                    'site_id'           => 1,
                    'field_name'        => $Row->field_name,
                    'field_label'       => $Row->field_title,
                    'field_type'        => 'textarea',
                    'settings'          => '{"field_ta_rows":"3","disallow_html_rendering":"y"}',
                    'author_id'         => 1,
                    'entry_date'        => strtotime('now'),
                    'edit_date'         => strtotime('now'),
                    'required'          => 'n',
                    'submissions_page'  => 'y',
                    'moderation_page'   => 'y',
                    'field_description' => '',
                );
            } else {
                echo $Row->field_name . '<br>';
            }

        }

        foreach($fields as $data){

            //ee()->db->insert('exp_freeform_fields', $data);

            //echo '<br/><br/>LINE: ' . __LINE__ . '<br/><pre>';
            //echo print_r($data,TRUE);
            //echo '</pre><br/><br/>';
            
        }
        */
        
        
        
        
            die('dead');

        return;
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
            if(!in_array(ucfirst($params['lib']), $this->_modFiles['libraries']) || !ee()->$params['lib']){
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
