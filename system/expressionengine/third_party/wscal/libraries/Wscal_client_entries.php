<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 */

class Wscal_client_entries extends Wscal_entity_abstract{

    /**
    *  define some constants to make life easier
    */
    const LIB_CLASS     = 'wscal_client_entries';
    const DISPLAY_NAME  = 'Client Case Studies';

    // main record model
    protected $_record_model_name = 'Wscal_client_entries_rmdl';
    
    // main view model
    protected $_view_model_name = 'Wscal_client_entries_vmdl';

    /**
    * Assign entries to member
    * @access public
    * @return string
    * @author Greg Crane
    */
    public function assign_entries() {

        $member_id = ee()->input->get_post('member_id', TRUE); 
        
        $ViewModel = $this->_get_vmdl();
        $params = array('where' => array('member_id' => $member_id));
        $result = $ViewModel->get_records(1, 0, $params);
        $Member = $result[key($result)];
        unset($result);
    
        $viewVars = array();
        $viewVars['member_id']          = ee()->input->get_post('member_id', TRUE); // member id
        $viewVars['form_action_url']    = $this->_action_assign;
        $viewVars['return_uri']         = base64_encode($this->_return_uri); // where "back" button goes or form redirects when saved
        $viewVars['Member']             = $Member;
        if($_field_data){
            $viewVars['_field_data']    = $_field_data; // needed for repopulating form values
        }
        
        // holds all unassigned entries for member
        $viewVars['available'] = array();

        $entries = ee()->channel_entries_model->get_entries(2, array('title'));
        foreach($entries as $Entry){
            $viewVars['available'][$Entry->entry_id] = array(
                                                            'entry_id' => $Entry->entry_id,
                                                            'title' => $Entry->title
                                                            );
        }
        unset($entries);

        // now get assigned for member
        $viewVars['assigned'] = array();
        
        ee()->load->model('view/wscal_client_entries_vmdl');
        $ClientEntriesViewModel = new Wscal_client_entries_vmdl;
        $queryParams = array(
                            'where' => array(
                                            'member_id' => $member_id
                                            ),
                            'order_by' => array(
                                            'title' => 'asc'
                                            )
                            );
        $entries = $ClientEntriesViewModel->get_records(0, 0, $queryParams);
       
        foreach($entries as $Entry){
            $viewVars['assigned'][] = array(
                                            'entry_id' => $Entry->entry_id,
                                            'title' => $Entry->title
                                            );
        }
        unset($entries);

        // set appropriate cp page title
        ee()->view->cp_page_title = 'Entry Assignments: ' . $Member->screen_name;
        
        return ee()->load->view('/admin/forms/form_assign_entries', $viewVars, TRUE);

    }
    
    /**
    * @access public
    * @return void
    * @author Greg Crane
    */
    public function action_assignment() {

        try{

            // this is the field group id
            if(!$field_group_id = ee()->input->post('primary_key')){
                throw new Exception('Missing Primary Key.');
            }

            ee()->load->model('qris_profile_fields_view_model');
            
            $ViewModel = new qris_profile_fields_view_model();
            $queryParams = array(
                                'where' => array(
                                                'field_group_id' => $field_group_id
                                                ),
                                );
            $fields = $ViewModel->find_records(0, 0, $queryParams);

            // loop results and reset group link
            ee()->load->model('qris_profile_fields_record_model');
            foreach($fields as $field){
                $RecordModel = new Qris_profile_fields_record_model();
                $RecordModel->load($field->field_id);
                $RecordModel->field_group_id = 0;
                $RecordModel->field_sort = 0;
                $RecordModel->save();
            }

            // now create new links
            $field_ids = ee()->input->post('field_ids');
            foreach($field_ids AS $sort_order=>$field_id){
                $sort = (int)$sort_order + 1;
                $RecordModel = new Qris_profile_fields_record_model();
                $RecordModel->load($field_id);
                $RecordModel->field_group_id = $field_group_id;
                $RecordModel->field_sort = $sort;
                $RecordModel->save();
            }

        } catch(Exception $e) {
            ee()->session->set_flashdata('message_failure', $e->getMessage());
            ee()->functions->redirect($this->_listing_page);
            exit();
        }

        // where are we going now?
        ee()->functions->redirect($this->_listing_page);

    }

}