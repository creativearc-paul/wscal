<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * @author      Greg Crane
 * @link        http://ghcrane.com
 */
abstract class Wscal_entity_abstract{

    // module name
    const MOD_NAME = 'wscal';
    
    // library class for routing
    const LIB_CLASS = '';
    
    // name to display in page titles
    const DISPLAY_NAME = '';

    // results per page for pagination in listings
    const PER_PAGE = 20;
        
    // main record model
    protected $_record_model_name = '';
    
    // main view model
    protected $_view_model_name = '';
    
    // record count for pagination
    protected $_pagination_record_count  = 0;

    // model columns for listing filters
    protected $_listing_filters = FALSE;
    
    // filter presets passed to listing page on initial call
    protected $_filter_presets = FALSE;
    
    // model columns that are dates
    protected $_date_fields = FALSE;
    
    // model columns that represent multi value fields like checkboxes, etc. where post values need to be imploded
    protected $_implode_fields = array();
    
    // model columns that represent bool fields like on/off (i.e. 1/0) that need to default to 0 if missing from post
    protected $_default_zero_fields = array();
    
    // model columns that should never be modified after record is first created
    protected $_never_modify_fields = array();
    
    // default model column for listing table sorting
    protected $_default_sort = array();
    
    // column names for our datatable, if there is a column key called delete_items in the $_datatable_columns array, the listing view will show a delete button
    protected $_datatable_columns = FALSE;
    
    // buttons on listing page above filters
    protected $_listing_buttons = FALSE;
    
    // warning for delete confirmation page
    protected $_delete_warning = 'These items will be permanently deleted!';
    
    // select options
    protected $_form_selects = array();
    
    // checkbox options
    protected $_form_checkboxes = array();

    
    /**
     * __construct
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function __construct(){
        
        // define BASE if needed
        if(!defined('BASE')){
            $s = (ee()->config->item('admin_session_type') != 'c') ? ee()->session->userdata('session_id') : 0;
            define('BASE', SELF . '?S=' . $s . AMP . 'D=cp');
        }

        // setup some urls
        // module url
        $this->_mod_url             = BASE . AMP . 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=' . $this::MOD_NAME;
        // base url for get requests
        $this->_lib_get_url         = $this->_mod_url . AMP . 'method=req' . AMP . 'lib=' . $this::LIB_CLASS;
        // url for post requests
        $this->_lib_post_url        = 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=' . $this::MOD_NAME . AMP . 'method=req' . AMP . 'lib=' . $this::LIB_CLASS;
        // theme url
        $this->_theme_url           = URL_THIRD_THEMES . $this::MOD_NAME;
        // listing request
        $this->_listing             = $this->_lib_get_url . AMP . 'libm=listing';
        // datatable callback ajax post request
        $this->_datatable_callback  = $this->_lib_post_url . AMP . 'libm=listing';
        // edit/create form request
        $this->_edit                = $this->_lib_get_url . AMP . 'libm=edit';
        // confirm delete post request
        $this->_confirm_delete      = $this->_lib_get_url . AMP . 'libm=confirm_delete';
        // delete post request
        $this->_action_delete       = $this->_lib_post_url . AMP . 'libm=action_delete';
        // save post request
        $this->_action_save         = $this->_lib_post_url . AMP . 'libm=action_save';

         // where to go after processing forms or canceling
        if(ee()->input->get('return_uri', TRUE)){
            $this->_return_uri = base64_decode(ee()->input->get('return_uri', TRUE));
        } elseif(ee()->input->post('return_uri', TRUE)) {
            $this->_return_uri = base64_decode(ee()->input->post('return_uri', TRUE));
        } else {
            $this->_return_uri = $this->_listing;
        }
        
        // create selects
        $this->SelectOptions = new stdClass();
        foreach($this->_form_selects as $field_name){
            $this->SelectOptions->$field_name = ee()->wscal_form_helper->get_select_options($field_name);
        }
        
        // create checkboxes
        $this->Checkboxes = new stdClass();
        foreach($this->_form_checkboxes as $field_name){
            $this->Checkboxes->$field_name = ee()->wscal_form_helper->get_checkboxes($field_name);
        }

    }
    
    /**
     * LISTING TABLE
     * @access public
     * @param
     * @return string
     * @author Greg Crane
     */
    public function listing() {

/*        // get filter presets if passed in url
        if(ee()->input->get('filter', TRUE)){
            // unencode
            if($filters = base64_decode(ee()->input->get('filter', TRUE))){
                if($filters = unserialize($filters)){
                    if(is_array($filters)){
                        $this->_filter_presets = $filters;
                    }
                }
            }
        }*/
        
        // page title
        ee()->view->cp_page_title = $this::DISPLAY_NAME;

        // initial datatable params
        $params = array(
                        'per_page'  => $this::PER_PAGE,
                        'lib'       => $this::LIB_CLASS
                        );
                        
        // initial state of the datatable
        $initial_state = array(
                                'sort'   => $this->_default_sort,
                                'offset' => 0
                                );
        
        // where ajax call is made to update datatable
        ee()->table->set_base_url($this->_datatable_callback);

        // set datatable columns
        ee()->table->set_columns($this->_datatable_columns);
        
        // initialize the datatable, everything past here happens on initial page load, but not on datatable ajax call
        $viewVars = ee()->table->datasource('listing_datasource', $initial_state, $params);

        // can we use this listing to delete items? do we show the delete button?
        $viewVars['confirm_delete'] = (key_exists('delete_items', $this->_datatable_columns)) ? $this->_confirm_delete: FALSE;

        // start constructing our return view
        $returnView = '';
        
        // add buttons view if set
        $returnView .= ($button_vars['buttons'] = $this->_listing_buttons) ? ee()->load->view('/admin/display/listing_buttons', $button_vars, TRUE): '';
        
        // add listing filters view if set
        if($this->_listing_filters){
            // ajax post back to self
            $filterVars['filter_form_action'] = $this->_listing;
            
            // keywords filter
            if(isset($this->_listing_filters['keyword_filter'])){
                $KeywordFilter = $this->_listing_filters['keyword_filter'];
                $Filter = new stdClass();
                $Filter->options = $KeywordFilter->options;
                //$Filter->selected = (ee()->input->post('keyword_filter', TRUE) && array_key_exists(ee()->input->post('keyword_filter', TRUE), $KeywordFilter->options)) ? ee()->input->post('keyword_filter', TRUE) : key($KeywordFilter->options); // if not passed, use first
                $Filter->keywords = (ee()->input->post('keywords', TRUE)) ? ee()->input->post('keywords', TRUE) : '';
                $filterVars['Keyword_filter'] = $Filter;
            }
            
            // dates filter
            if(isset($this->_listing_filters['date_range_filter'])){
                $DateRangeFilter = $this->_listing_filters['date_range_filter'];
                $Filter = new stdClass();
                $Filter->options = $DateRangeFilter->options;
                //$Filter->selected = (ee()->input->post('date_filter', TRUE) && array_key_exists(ee()->input->post('date_filter', TRUE), $DateRangeFilter->options)) ? ee()->input->post('date_filter', TRUE) : key($DateRangeFilter->options); // if not passed, use first
                $Filter->start_date = (ee()->input->post('start_date', TRUE)) ? ee()->input->post('start_date', TRUE): '';
                $Filter->end_date = (ee()->input->post('end_date', TRUE)) ? ee()->input->post('end_date', TRUE): '';
                $filterVars['Date_filter'] = $Filter;
                $filterVars['theme_url'] = $this->_theme_url;
            }
            
            // select filter
            if(isset($this->_listing_filters['select_filters'])){
                foreach($this->_listing_filters['select_filters'] AS $SelectFilter){
                    $Filter = new stdClass();
                    $Filter->label = $SelectFilter->label;
                    $Filter->column = $SelectFilter->column;
                    $Filter->options = $SelectFilter->options;
                    $filterVars['select_filters'][] = $Filter;
                }
            }

            // load filters view
            $returnView .= ee()->load->view('/admin/display/listing_filters', $filterVars, TRUE);
            
        }
        
        // load listing table view
        $returnView .= ee()->load->view('/admin/display/listing_table', $viewVars, TRUE);
                        
        return $returnView;

    }

    /**
     * LISTING TABLE DATASOURCE
     * @access public
     * @return array
     * @author Greg Crane
     */
    abstract function listing_datasource(array $state, array $params);

    /**
     * Create or edit form
     * @access public
     * @return string
     * @author Greg Crane
     */
    public function edit() {
        $RecordModel = (ee()->input->get('pk', TRUE)) ? $this->_get_rmdl(ee()->input->get('pk', TRUE)) : $this->_get_rmdl();
        return $this->_get_form_view($RecordModel);
    }

    /**
     * confirm delete page
     * @access public
     * @return string view with hidden delete form
     * @author Greg Crane
     */
    public function confirm_delete() {

        // we need an array of items primary keys to delete
        try{
            if(!is_array(ee()->input->post('delete_items', TRUE))){
                throw new Exception('Nothing selected to delete.');
            }
        } catch(Exception $e) {
            // nothing posted, nothing to delete, bail back to the listing page
            ee()->session->set_flashdata('message_failure', $e->getMessage());
            ee()->functions->redirect($this->_listing);
            exit();
        }
        
        $viewVars = array();
        
        // set delete keys
        $record_ids = $viewVars['delete_keys'] = ee()->input->post('delete_items', TRUE);
        
        // cancel back to listing page unless told otherwise
        $viewVars['form_action_url'] = $this->_action_delete;
            
        // get display table data
        $viewVars['tables'] = $this->_confirm_delete_tables($record_ids);
        $viewVars['return_uri'] = base64_encode($this->_return_uri);

        // set appropriate cp page title and stern warning text
        ee()->view->cp_page_title = 'Confirm Deletion';
        $viewVars['warning'] = $this->_delete_warning;
        
        return ee()->load->view('/admin/forms/confirm_delete', $viewVars, TRUE);
    }

    /**
     * save action
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function action_save() {

        // load Record
        $RecordModel = (ee()->input->post('pk', TRUE)) ? $this->_get_rmdl(ee()->input->post('pk', TRUE)) : $this->_get_rmdl();

        // now validate form
        $rules = ee()->config->item('wscal_validation_rules');
        ee()->form_validation->set_rules($rules[$this::LIB_CLASS])->set_error_delimiters('<div class="error">', '</div>');
        
        if(!ee()->form_validation->run()){ // validation failed, have to reload form
            return $this->_get_form_view($RecordModel, ee()->form_validation->_field_data);
        }

        ///////////////// we passed validation, onward! ///////////////////
        
        // unset fields if needed
        foreach($this->_never_modify_fields as $field) {
            unset($RecordModel->$field);
        }
        
        // get data from post and populate $RecordModel
        foreach($RecordModel as $field=>$value) {
            if(isset($_POST[$field])){
                
                // convert post data if needed
                // date type fields
                if(key_exists($field, $this->_date_fields) && $this->_date_fields[$field] === 'date'){
                    $date = strtotime(ee()->security->xss_clean($_POST[$field]));
                    if($date){
                        $RecordModel->$field = date('Y-m-d', $date);
                    } else {
                        $RecordModel->$field = '0000-00-00';
                    }
                } elseif(in_array($field, $this->_implode_fields)){
                    $RecordModel->$field = ee()->security->xss_clean(implode(',', $_POST[$field]));
                } elseif(in_array($field, $this->_default_zero_fields)){
                    $RecordModel->$field = 0;
                } else {
                    $RecordModel->$field = ee()->security->xss_clean($_POST[$field]);
                }

            }
        }
        
        try{
            if(!$RecordModel->save()){
                throw new Exception('Unable to save record.');
            }
        } catch(Exception $e) {
            // couldn't save record
            ee()->session->set_flashdata('message_failure', $e->getMessage());
            ee()->functions->redirect($this->_return_uri);
        }
        
        // post save processing
        try{
            if(!$this->_post_save($RecordModel)){
                throw new Exception('Post-save processing failed.');
            }
        } catch(Exception $e) {
            // couldn't do post save processing
            ee()->session->set_flashdata('message_failure', $e->getMessage());
            ee()->functions->redirect($this->_return_uri);
        }

        ee()->functions->redirect($this->_return_uri);
    }
    
    /**
     * post-save processing
     * @access public
     * @return bool
     * @author Greg Crane
     */
    public function _post_save($RecordModel){
        return TRUE;
    }

	/**
     * delete action
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function action_delete() {
    }

    /**
     * GET MODULE URLS
     * @access public
     * @return string
     * @author Greg Crane
     */
    public function get_url($type = 'listing'){
        switch($type){
            case 'export': 
                return $this->_export;
                break;
            case 'edit': 
                return $this->_edit;
                break;
            case 'listing':
                return $this->_listing;
                break;
        }
        return $this->_listing;
    }

    /**
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function get_datatable_results($ViewModel, $state, $params, $select_columns = FALSE){
        
        $queryParams = array();
        
        // add column sort param
        if(isset($state['sort'])){
            $queryParams['order_by'] = $state['sort'];
        }
        
        // add columns to select, if this is not set, you'll get all the columns defined in the view model and that might be a lot of unneeded data
        if($select_columns && is_array($select_columns)){
            $queryParams['select'] = $select_columns;
        }

        // add keyword param
        if($keywords = ee()->input->post('keywords', TRUE)){
            $keyword_filter_selected = (ee()->input->post('keyword_filter', TRUE) && array_key_exists(ee()->input->post('keyword_filter', TRUE), $this->_listing_filters['keyword_filter']->options)) ? ee()->input->post('keyword_filter', TRUE) : key($this->_listing_filters['keyword_filter']->options);
            $queryParams['like'][$keyword_filter_selected] = $keywords;
        }
        
        // add date param
        if(ee()->input->post('start_date', TRUE) || ee()->input->post('end_date', TRUE)){
            $date_filter_selected = (ee()->input->post('date_filter', TRUE) && array_key_exists(ee()->input->post('date_filter', TRUE), $this->_listing_filters['date_range_filter']->options)) ? ee()->input->post('date_filter', TRUE) : key($this->_listing_filters['date_range_filter']->options);
            
            $start_date = (ee()->input->post('start_date', TRUE)) ? strtotime(trim(ee()->input->post('start_date', TRUE))) : '';
            $queryParams['date_range'][$date_filter_selected]['start_date'] = $start_date;
            
            $end_date = (ee()->input->post('end_date', TRUE)) ? strtotime(trim(ee()->input->post('end_date', TRUE))) : '';
            $queryParams['date_range'][$date_filter_selected]['end_date'] = $end_date;
        }
        
        // add select filter params
        // select filter
        if(isset($this->_listing_filters['select_filters'])){
            foreach($this->_listing_filters['select_filters'] AS $SelectFilter){
                if($selectedVal = ee()->input->post($SelectFilter->column, TRUE)){
                    if($selectedVal != ''){
                        $queryParams['where'][$SelectFilter->column] = $selectedVal;
                    }
                }
            }
        }
        
        // set total records for pagination
        $this->_pagination_record_count = $ViewModel->get_records_count($queryParams);
        
        return $ViewModel->get_records($params['per_page'], $state['offset'], $queryParams);
    }

    /**
     * convert dates to and from different formats
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function format_date($date_string, $type, $direction){
        switch($type){
            case 'date': 
                if($direction == 'input'){
                    
                } else {
                    if($date_string != '' && $date_string != 0 && $date_string != '0000-00-00'){
                        return date('n/j/Y', strtotime($date_string));
                    } else {
                        return '';
                    }
                }
                break;
            case 'datetime': 
                if($direction == 'input'){
                    
                } else {
                    if($date_string != '' && $date_string != 0 && $date_string != '0000-00-00 00:00:00'){
                        return date('n/j/Y , g:i a', strtotime($date_string));
                    } else {
                        return '';
                    }
                }
                break;
        }
    }

    /**
     * gets a record model
     * @access public
     * @param $pk
     * @return mixed object $RecordModel or redirects to listing page
     * @author Greg Crane
     */
    public function _get_rmdl($pk = NULL) {
        
        // load model
        ee()->load->model('record/' . strtolower($this->_record_model_name));

        try{
            
            // create new record model
            if(!$RecordModel = new $this->_record_model_name()){
                throw new Exception('Unable to load record model.');
            }
            if(is_null($pk)){
                return $RecordModel;
            }
            
            // if primary_key passed, we're editing, so load model with record
            if(!$RecordModel->load((int)$pk)){
                throw new Exception('Unable to load record.');
            }
        } catch(Exception $e) {
            // something failed, bail back to the listing page
            ee()->session->set_flashdata('message_failure', $e->getMessage());
            ee()->functions->redirect($this->_listing);
            exit();
        }

        return $RecordModel;
    }
    
    /**
     * gets a view model
     * @access public
     * @return object $ViewModel
     * @author Greg Crane
     */
    public function _get_vmdl() {
                
        // load model
        ee()->load->model('view/' . strtolower($this->_view_model_name));

        try{
            
            // create new view model
            if(!$ViewModel = new $this->_view_model_name()){
                throw new Exception('Unable to load view model.');
            }

        } catch(Exception $e) {
            // something failed, bail back to the listing page
            ee()->session->set_flashdata('message_failure', $e->getMessage());
            ee()->functions->redirect($this->_listing);
            exit();
        }

        return $ViewModel;
    }
    
}