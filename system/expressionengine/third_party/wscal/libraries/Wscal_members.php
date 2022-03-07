<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 */

class Wscal_members extends Wscal_entity_abstract{

    /**
    *  define some constants to make life easier
    */
    const LIB_CLASS     = 'wscal_members';
    const DISPLAY_NAME  = 'Manage Member Accounts';

    // main record model
    protected $_record_model_name = 'Wscal_clients_rmdl';
    
    // main view model
    protected $_view_model_name = 'Wscal_members_vmdl';

    /**
    * default column to sort listing view datatable
    * @var array
    */                                         
    protected $_default_sort = array('join_date' => 'asc');
    
    /**
    * columns used in our display table, keys are view model column aliases so we have sorting. The key column_name may not be the exact content of the 
    * table cell, for example we may wish to sort by person_last_name yet display $Result->person_full_name or a composite of multiple data items in table cell
    * @var array
    */
    protected $_datatable_columns = array(
                                        'group_id'          => array('header' => array('data' => 'Status', 'style' => 'width:90px;text-align:center;')),
                                        'username'          => array('header' => array('data' => 'Member<span style="float:right;">Password</span>', 'style' => 'width:250px;')),
                                        'email'             => array('header' => array('data' => 'Email', 'style' => '')),
                                        'expiration_date'   => array('header' => array('data' => 'Account Expires', 'style' => 'width:150px;')),
                                        'permission_entry_id' => array('header' => array('data' => 'Member Permissions', 'style' => '')),
                                        'join_date'         => array('header' => array('data' => 'Join Date', 'style' => 'width:150px;text-align:center;')),
                                        'delete_items'      => array('header' => array('data' => 'Delete', 'style' => 'text-align:center;width:60px;'), 'sort' => FALSE)
                                        );

    /**
    * define column aliases that represent date related items so we know how to convert when saving or displaying
    * @var array 'model_column_alias' => 'mysql_format', date, time, datetime, timestamp, unixtime, etc.
    */                                    
    protected $_date_fields = array(
                                    'join_date' => 'unixtime',
                                    'last_visit' => 'unixtime',
                                    'last_activity' => 'unixtime',
                                    'expiration_date' => 'date'
                                    );
                                    
        // select options
    protected $_form_selects = array(
                                    'member_groups',
                                    'member_permissions_entry_id'
                                    );
                                    
    /**
    * warning for delete confirmation page
    * @var string
    */ 
    protected $_delete_warning = 'The following member accounts will be permanantly deleted!';
    
    /**
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function __construct() {
        
        parent::__construct();
        
        // where to go after processing form or canceling
        if(ee()->session->cache('wscal', 'redirect_uri')){
            $this->_return_uri = base64_decode(ee()->session->cache('wscal', 'redirect_uri'));
        } elseif(ee()->input->get_post('return_uri', TRUE)){
            $this->_return_uri = base64_decode(ee()->input->get_post('return_uri', TRUE));
        } else {
            $this->_return_uri = $this->_listing;
        }
        
        // assign entries form request
        $this->_assign_entries = $this->_lib_get_url . AMP . 'libm=assign_entries';
        
        // assign post request
        $this->_action_assign = $this->_lib_post_url . AMP . 'libm=action_assign';

        /**
        * define listing filters
        */
        $KeywordFilter = new stdClass();
        $KeywordFilter->options = array(
                                        'username'  => 'User Name',
                                        'email'     => 'Email'
                                        );
        $this->_listing_filters['keyword_filter'] = $KeywordFilter;
        
        $DateRangeFilter = new stdClass();
        $DateRangeFilter->options = array(
                                        'join_date' => 'Join Date',
                                        'expiration_date' => 'Expiration Date'
                                        );
        $this->_listing_filters['date_range_filter'] = $DateRangeFilter;

        $SelectFilter = new stdClass();
        $SelectFilter->label = 'Status';
        $SelectFilter->column = 'group_id';
        $SelectFilter->options = $this->SelectOptions->member_groups;
        $this->_listing_filters['select_filters'][] = $SelectFilter;
        
        $SelectFilter = new stdClass();
        $SelectFilter->label = 'Member Permissions';
        $SelectFilter->column = 'permission_entry_id';
        $SelectFilter->options = $this->SelectOptions->member_permissions_entry_id;
        $this->_listing_filters['select_filters'][] = $SelectFilter;

        /**
        * multidimensional array for creating buttons for top of listing page - array(array('url' => $this->_edit, 'label' => 'Edit Item'))
        * created here since we are using class variables created in the abstract
        */
        $registerLink = cp_url('members/new_member_form', array());
        $this->_listing_buttons = array(
                                        array(
                                            'url'   => $registerLink,
                                            'label' => 'New Member Account'
                                            )
                                        );
    }

    /**
     * Data source for our listing table
     * @access public
     * @return array
     * @author Greg Crane
     */
    public function listing_datasource(array $state, array $params){
        
        // load the main view model for this listing table and get results
        $ViewModel = $this->_get_vmdl();
        $results = $this->get_datatable_results($ViewModel, $state, $params);

        $tableRows = array();
        foreach($results as $Row) {
            
            $deleteCell = form_checkbox(array(  'name'  => 'delete_items[]', 
                                                'id'    => 'delete_check_box_' . $Row->member_id, 
                                                'value' => $Row->member_id, 
                                                'class' => 'deleteCheckBox'));
            
            // edit member link
            $editLink = cp_url('myaccount', array(
                                                'id' => $Row->member_id
                                                ));
                                                
            // edit password link
            $passwordLink = cp_url('myaccount/username_password', array(
                                                                    'id' => $Row->member_id
                                                                    ));
                                                
            // add status
            $status = ($Row->group_id == 7) ? '<span style="color:#ff0000;">Expired</span>' : '<span style="color:#333333;">Active</span>';
            
            // email link
            $emailLink = cp_url('tools_communicate', array(
                                                        'email_member' => $Row->member_id
                                                        ));
                                                        
            // expiration link icon
            $expirationIcon = ($Row->display_expiration_date == '') ? 'add.png' : 'edit.png';
                                                        
            // url link icon
            $urlIcon = ($Row->permission_entry_id) ? 'edit.png' : 'add.png';
            
            // add db result $Row to array used in constructing listing table
            $tableRows[] = array(
                            'group_id'          => array('data' => $status, 'style' => 'text-align:center;'),
                            'username'          => '<a href="' . $editLink . '">' . $Row->member_id . ' - ' . $Row->username . '</a><a href="' . $passwordLink . '" style="float:right;margin-right:15px;"><img src="' . URL_THIRD_THEMES . 'wscal/images/password.png"></a>',
                            'email'             => array('data' => '<a href="' . $emailLink . '"><img src="' . URL_THIRD_THEMES . 'wscal/images/email.png" style="padding-right:10px;">' . $Row->email . '</a>', 'style' => ''),
                            'expiration_date'   => array('data' => '<a href="' . $this->_edit . AMP . 'pk=' . $Row->id . AMP . 'mid=' . $Row->member_id . '"><img src="' . URL_THIRD_THEMES . 'wscal/images/' . $expirationIcon . '" style="padding-right:10px;">' . $Row->display_expiration_date . '</a>', 'style' => ''),
                            'permission_entry_id' => array('data' => '<!--a href="' . $this->_assign_entries . AMP . 'member_id=' . $Row->member_id . '">.</a--> <a href="' . $this->_edit . AMP . 'pk=' . $Row->id . AMP . 'mid=' . $Row->member_id . '"><img src="' . URL_THIRD_THEMES . 'wscal/images/' . $urlIcon . '" style="padding-right:10px;">' . $Row->title . '</a>', 'style' => ''),
                            'join_date'         => array('data' => $Row->display_join_date, 'style' => 'text-align:center;'),
                            'delete_items'      => array('data' => $deleteCell, 'style' => 'text-align:center;')
                            );
        }

        // data array used by sortable table
        $returnData = array(
                            'rows'       => $tableRows,
                            'pagination' => array(
                                                'per_page'   => $params['per_page'],
                                                'total_rows' => $this->_pagination_record_count
                                                ),
                            'no_results' => '<div style="padding:20px;">Nothing to display.</div>'
                            );

        return $returnData;

    }

    /**
     * get view vars for main form
     * @access protected
     * @param string $record_ids
     * @return array $tables
     * @author Greg Crane
     */
    protected function _get_form_view($RecordModel, $_field_data = FALSE){

        // get view model to display additional data in form if editing an existing record
        if($RecordModel->id != ''){
            $ViewModel = $this->_get_vmdl();
            $params = array('where' => array('id' => $RecordModel->id));
            $result = $ViewModel->get_records(1, 0, $params);
            $View = (count($result) > 0) ? $result[key($result)] : new stdClass();
            unset($result);
        } else {
            $View = new stdClass();
        }
        
        // convert dates for display/date-pickers
        foreach($this->_date_fields as $field=>$type){
            if(isset($RecordModel->$field)){
                $RecordModel->$field = $this->format_date($RecordModel->$field, $type, 'output');
            }
        }
    
        $viewVars = array();
        $viewVars['pk']                 = ee()->input->get_post('pk', TRUE);
        $viewVars['member_id']          = ee()->input->get_post('mid', TRUE); // member id
        $viewVars['form_action_url']    = $this->_action_save;
        $viewVars['return_uri']         = base64_encode($this->_return_uri); // where "back" button goes or form redirects when saved
        $viewVars['Record']             = $RecordModel;
        $viewVars['View']               = $View;
        $viewVars['SelectOptions']      = $this->SelectOptions;
        if($_field_data){
            $viewVars['_field_data']    = $_field_data; // needed for repopulating form values
        }
        
        // set appropriate cp page title
        ee()->view->cp_page_title = (isset($View->username)) ? 'Editing Account: ' . $View->username : '';
        
        return ee()->load->view('/admin/forms/form_member', $viewVars, TRUE);
        
    }
    
    /**
     * post-save processing
     * @access public
     * @return bool
     * @author Greg Crane
     */
    public function _post_save($RecordModel){
        
        // if expiration date is today or after, set the member group to active else set to expired
        if($RecordModel->expiration_date >= date("Y-m-d")){
            $data = array(
                        'group_id' => 5
                        );
            ee()->db->where('member_id', $RecordModel->member_id);
            ee()->db->update('exp_members', $data);
        } else {
            $data = array(
                        'group_id' => 7
                        );
            ee()->db->where('member_id', $RecordModel->member_id);
            ee()->db->update('exp_members', $data);
        }
        return TRUE;
    }
    
    /**
     * creates table of items that will be deleted
     * @access protected
     * @param string $record_ids
     * @return array $tables
     * @author Greg Crane
     */
    protected function _confirm_delete_tables($record_ids){
        
        $tables = array();
        
        // load view model so we can display more useful info about what we're getting rid of than just the primary key
        $ViewModel = $this->_get_vmdl();
        $params = array(
                        'where_in' => array('member_id' => $record_ids)
                        );
        $result = $ViewModel->get_records(0, 0, $params);
        $Table = new stdClass();
        $Table->table_caption = 'Members to be deleted.';
        $Table->table_headers = array(
                                    'ID', 
                                    'Join Date',
                                    'Username',
                                    'Email'
                                    );
        
        $Table->table_rows = array();                     
        foreach($result as $Row) {
            $Table->table_rows[] = array(
                                        $Row->member_id,
                                        $Row->display_join_date,
                                        $Row->username,
                                        $Row->email
                                        );  
        }
        $tables[] = $Table;
        return $tables;
    }

    /**
     * delete action
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function action_delete() {
        
        ee()->functions->redirect($this->_listing);
        
    }

    /**
     * construct_csv
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function export() {

        // load models
        ee()->load->model('export/Wscal_export_members_model');       
        $ExportModel = new Wscal_export_members_model();
        $rows = $ExportModel->get_data();
        
        // load download helper
        ee()->load->helper('download');
        $data = '';
        foreach($rows as $Row){
            // new line
            $line = '';
            foreach($Row as $cell){
                $line .= '"' . str_replace('"', '""', $cell) . '",';
            }
            $data .= rtrim($line, ",") . "\n";
        }
        force_download('export_' . date('n-j-Y_gi_a') . '.csv', $data);
        exit;

    }

    /**
     * generate a random alpha numeric string
     * @param int $length
     * @access public
     * @return string
     */
    private function _generate_password($length = 32) {
        $chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        $pass = '';
        for($i = 0; $i < $length; $i++) {
            $pass .= $chars[mt_rand(0, count($chars) - 1)];
        }
        return $pass;
    }

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
        
        // get client entry model
        ee()->load->model('view/wscal_client_entries_vmdl');
        $ClientEntriesViewModel = new Wscal_client_entries_vmdl;
        
        // holds all unassigned entries for member
        $viewVars['available'] = array();        

        $entries = $ClientEntriesViewModel->get_entries();
        foreach($entries as $Entry){
            
            // get categories (i.e. group, financial, industrial, etc.)
            $categories = $ClientEntriesViewModel->get_entry_categories($Entry->entry_id);
            $thumb = str_replace('{filedir_3}','http://casestudies.staging.creativearc.com/assets/projects/',$Entry->field_id_5);
            $viewVars['available'][$Entry->entry_id] = array(
                                                            'entry_id' => $Entry->entry_id,
                                                            'title' => $Entry->title,
                                                            'thumb' => $thumb,
                                                            'categories' => $categories
                                                            );
        }
        unset($entries);

        // now get assigned for member
        $viewVars['assigned'] = array();
        
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
            
            // get categories (i.e. group, financial, industrial, etc.)
            $categories = $ClientEntriesViewModel->get_entry_categories($Entry->entry_id);
            $thumb = str_replace('{filedir_3}','http://casestudies.staging.creativearc.com/assets/projects/',$Entry->field_id_5);
            $viewVars['assigned'][$Entry->entry_id] = array(
                                            'entry_id' => $Entry->entry_id,
                                            'title' => $Entry->title,
                                            'thumb' => $thumb,
                                            'categories' => $categories
                                            //{project_thumbnail_image}
                                            );
            // remove from available entries
            unset($viewVars['available'][$Entry->entry_id]);
        }
        unset($entries);

        // set appropriate cp page title
        ee()->view->cp_page_title = 'Entry Assignments: ' . $Member->screen_name;
        
        return ee()->load->view('/admin/forms/form_assign_entries', $viewVars, TRUE);

    }
    
    /**
    * Assign action
    * @access public
    * @return string
    * @author Greg Crane
    */
    public function action_assign() {

        try{

            // get $member_id
            if(!$member_id = ee()->input->post('member_id')){
                throw new Exception('Missing Member.');
            }
            
            // delete current assigned entries
            ee()->load->model('record/wscal_client_entries_rmdl');
            $RecordModel = new Wscal_client_entries_rmdl();
            $RecordModel->delete_where(array('member_id' => $member_id));
            
            // now create new links
            $entry_ids = ee()->input->post('entry_ids');

            foreach($entry_ids AS $sort_order=>$entry_id){
                $sort = (int)$sort_order + 1;
                $RecordModel = new Wscal_client_entries_rmdl();
                $RecordModel->member_id = $member_id;
                $RecordModel->entry_id  = $entry_id;
                $RecordModel->sort      = $sort;
                $RecordModel->save();
                unset($RecordModel);
            }

        } catch(Exception $e) {
            ee()->session->set_flashdata('message_failure', $e->getMessage());
            ee()->functions->redirect($this->_listing);
            exit();
        }

        // where are we going now?
        ee()->functions->redirect($this->_listing);

        
    }
    
}