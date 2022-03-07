<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 */

class Neoseo_channel extends Neoseo_entity_abstract{

    const MOD_NAME = 'neoseo';
    
    /**
    *  define some constants to make life easier
    */
    const LIB_CLASS     = 'neoseo_channel';
    const DISPLAY_NAME  = 'Channel Settings / Defaults';
    
                        
    // main record model
    protected $_record_model_name = 'Neoseo_channel_rmdl';
    
    // main view model
    protected $_view_model_name = 'Neoseo_channel_vmdl';

    /**
    * default column to sort listing view datatable
    * @var array
    */                                         
    protected $_default_sort = array('channel_title' => 'asc');
    
    /**
    * columns used in our display table, keys are view model column aliases so we have sorting. The key column_name may not be the exact content of the 
    * table cell, for example we may wish to sort by person_last_name yet display $Result->person_full_name or a composite of multiple data items in table cell
    * @var array
    */
    protected $_datatable_columns = array(
                                        'channel_id'    => array('header' => array('data' => 'Channel ID', 'style' => 'width:110px;')),
                                        'manage_channel'=> array('header' => array('data' => 'Managed', 'style' => 'width:100px;')),
                                        'channel_title' => array('header' => array('data' => 'Channel', 'style' => '')),
                                        );
        
    /**
    * define column aliases that represent date related items so we know how to convert when saving or displaying
    * @var array 'model_column_alias' => 'mysql_format', date, time, datetime, timestamp, unixtime, etc.
    */                                    
    protected $_date_fields = array(
                                    'created_at' => 'datetime',
                                    'updated_at' => 'datetime'
                                    );
    
    private $_ee_channels = array();
    private $_module_channels = array();
    
    // select options
    protected $_form_selects = array(
                                    'channels',
                                    'twitter_card_type_ovid',
                                    'open_graph_type_ovid',
                                    'no_yes',
                                    'no_yes_char',
                                    'channel_fields',
                                    'meta_robots_ovid'
                                    );                                
				    
    protected $_admin_form = 'form_channel';
    
    /**
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function __construct() {

        parent::__construct();
        


        // do some cleanup
        $this->_sync_channels();

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
        
        // for tab cleanup
        ee()->load->library('layout');

        $tableRows = array();
        foreach($results as $Row) {
            
            // cleanup layout tabs
            if($Row->manage_channel === 'n'){
                ee()->layout->delete_layout_tabs($this->tabs(), 'neoseo', $Row->channel_id);
                $managedStyle = 'text-align:center;color:#cccccc;';
            } else {
                $managedStyle = 'text-align:center;font-weight:bold;';
            }
            
            // add db result $Row to array used in constructing listing table
            $tableRows[] = array(
                                'channel_id'    => array('data' => $Row->channel_id, 'style' => 'text-align:center;'),
                                'manage_channel'=> array('data' => $Row->display_manage_channel, 'style' => $managedStyle),
                                'channel_title' => array('data' => '<a href="' . $this->_edit . AMP . 'pk=' . $Row->channel_id . '">' . $Row->channel_title . '</a>')
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
        if($RecordModel->{$RecordModel::DB_TABLE_PK} != ''){
            $ViewModel = $this->_get_vmdl();
            $params = array('where' => array($RecordModel::DB_TABLE_PK => $RecordModel->{$RecordModel::DB_TABLE_PK}));
            $result = $ViewModel->get_records(1, 0, $params);
            $RecordView = (count($result) > 0) ? $result[key($result)] : new stdClass();
        }

        // convert dates for display/date-pickers
        foreach($this->_date_fields as $field=>$type){
            if(isset($RecordModel->$field)){
                $RecordModel->$field = $this->format_date($RecordModel->$field, $type, 'output');
            }
        }

        $viewVars = array();
        $viewVars['pk']                 = ee()->input->get_post('pk', TRUE);
        $viewVars['form_action_url']    = $this->_action_save;
        $viewVars['return_uri']         = base64_encode($this->_return_uri); // where "back" button goes or form redirects when saved
        $viewVars['Record']             = $RecordModel;
        $viewVars['RecordView']         = (isset($RecordView)) ? $RecordView : new stdClass();
        $viewVars['SelectOptions']      = $this->SelectOptions; // arrays for populating each select fields in view
        // modify channel fields so is just what we need
        $viewVars['SelectOptions']->channel_fields = $viewVars['SelectOptions']->channel_fields[$RecordView->channel_id];
        if($_field_data){
            $viewVars['_field_data']    = $_field_data; // needed for repopulating form values
        }
        
        // set appropriate cp page title
        ee()->view->cp_page_title = 'Channel Settings / Defaults: ' . $RecordView->channel_title . ' (Channel ID: ' . $RecordView->channel_id . ')';
        
        return ee()->load->view('/admin/forms/' . $this->_admin_form, $viewVars, TRUE);

    }

    /**
     * creates table of items that will be deleted
     * @access protected
     * @param string $record_ids
     * @return array $tables
     * @author Greg Crane
     */
    protected function _confirm_delete_tables($record_ids){
        return array();
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
        $rules = ee()->config->item('neoseo_validation_rules');
        ee()->form_validation->set_rules($rules['neoseo_channel']);
        ee()->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        if(ee()->form_validation->run() == FALSE){ // validation failed, have to reload form
            return $this->_get_form_view($RecordModel, ee()->form_validation->_field_data);
        }

        ///////////////// we passed validation, onward! ///////////////////

        // clone record object so we can do a comparison of "managed" flag
        $OriginalRecord = clone($RecordModel);
        
        // only want valid record fields
        foreach($RecordModel as $field=>$value) {
            // get from $_POST
            if(isset($_POST[$field])){
                $RecordModel->$field = ee()->security->xss_clean($_POST[$field]);
            }
        }
        
        // add files to record
        $RecordModel->open_graph_image = (ee()->input->post('open_graph_image_hidden_file', TRUE) != '') ? '{filedir_' . ee()->input->post('open_graph_image_hidden_dir', TRUE) . '}' . ee()->input->post('open_graph_image_hidden_file', TRUE) : '';
        $RecordModel->twitter_card_image = (ee()->input->post('twitter_card_image_hidden_file', TRUE) != '') ? '{filedir_' . ee()->input->post('twitter_card_image_hidden_dir', TRUE) . '}' . ee()->input->post('twitter_card_image_hidden_file', TRUE) : '';
        
        try{
            if(!$RecordModel->save()){
                throw new Exception('Unable to save record.');
            }
        } catch(Exception $e) {
            // couldn't save record
            ee()->session->set_flashdata('message_failure', $e->getMessage());
            ee()->functions->redirect($this->_return_uri);
        }

        // update tabs as needed
        ee()->load->library('layout');
        ee()->layout->delete_layout_tabs($this->tabs(), 'neoseo', $RecordModel->channel_id);
        if($RecordModel->manage_channel === 'y'){
            ee()->layout->add_layout_tabs($this->tabs(), 'neoseo', $RecordModel->channel_id);
        }

        ee()->load->model('record/Neoseo_entry_rmdl');
        // delete entries if needed
        if($RecordModel->manage_channel === 'n'){
            $EntryDataModel = new Neoseo_entry_rmdl();
            $where = array('channel_id' => $RecordModel->channel_id);
            $EntryDataModel->delete_where($where);
            unset($EntryDataModel);
        }
        
        // add entries if needed
        if($RecordModel->manage_channel === 'y'){
            
            // see if entries already exist
            $EntryDataModel = new Neoseo_entry_rmdl();
            $where = array('channel_id' => $RecordModel->channel_id);
            $count = $EntryDataModel->count_rows_where($where);
            unset($EntryDataModel);
            
            if($count === 0){
                
                // check if old seo module data exists
                $oldSeoExists = FALSE;
                if(ee()->db->table_exists('exp_seo_data') 
                    && ee()->db->field_exists('channel_id', 'exp_seo_data') 
                    && ee()->db->field_exists('entry_id', 'exp_seo_data')
                    && ee()->db->field_exists('channel_id', 'exp_seo_data')
                    && ee()->db->field_exists('title', 'exp_seo_data')
                    && ee()->db->field_exists('keywords', 'exp_seo_data')
                    && ee()->db->field_exists('description', 'exp_seo_data')){
                    $oldSeoExists = TRUE;
                }
                
                // grab all the entries for the channel
                ee()->db->select('entry_id, title');
                ee()->db->where('channel_id', $RecordModel->channel_id);
                $query = ee()->db->get('exp_channel_titles');
                $ee_entries =  $query->result();
                
                // add each entry into neoseo table
                foreach($ee_entries as $EeEntry){
                    
                    $EntryModel = new Neoseo_entry_rmdl();
                    $EntryModel->entry_id = $EeEntry->entry_id;
                    $EntryModel->channel_id = $RecordModel->channel_id;
                    $EntryModel->title_tag = $EeEntry->title;
                    
                    // grab old seo data
                    if($oldSeoExists){
                        $oldSeoQuery = ee()->db->get_where('exp_seo_data', array('entry_id' => $EeEntry->entry_id));
                        if($oldSeoQuery->num_rows() === 1){
                            $OldSeoResult = $oldSeoQuery->row();
                            if($OldSeoResult->title != ''){
                                $EntryModel->title_tag = $OldSeoResult->title;
                            }
                            if($OldSeoResult->keywords != ''){
                                $EntryModel->meta_keywords = $OldSeoResult->keywords;
                            }
                            if($OldSeoResult->description != ''){
                                $EntryModel->meta_description = $OldSeoResult->description;
                            }
                        }
                    }
                                        
                    $EntryModel->insert_with_key();
                    unset($EntryModel);
                }
                
            }
            
        }

        ee()->functions->redirect($this->_return_uri);
        
    }

    /**
     * delete action
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function action_delete() {
        
        ee()->functions->redirect($this->_return_uri);
        
    }
    
    /**
     * sync module channels with ee channels
     * @access private
     * @return void
     * @author Greg Crane
     */
    private function _sync_channels() {
        
        return;
        
        ee()->api->instantiate('channel_structure');
        $channelsQuery = ee()->api_channel_structure->get_channels();
        $channels = $channelsQuery->result();
        foreach($channels as $channel){
            $this->_ee_channels[] = $channel->channel_id;
        }
        
        // remove old
        $ViewModel = $this->_get_vmdl();
        $params = array('select' => array('channel_id'));
        $result = $ViewModel->get_records(0, 0, $params);
        
        ee()->load->model('record/Neoseo_entry_rmdl');
        foreach($result as $CurrentChannel){
            if(!in_array($CurrentChannel->channel_id, $this->_ee_channels)){
                // remove entries
                $EntryDataModel = new Neoseo_entry_rmdl();
                $where = array('channel_id' => $CurrentChannel->channel_id);
                $EntryDataModel->delete_where($where);
                unset($EntryDataModel);
                // remove channel
                $RecordModel = $this->_get_rmdl($CurrentChannel->channel_id);
                $RecordModel->delete();
                unset($RecordModel);
            } else {
                $this->_module_channels[] = $CurrentChannel->channel_id;
            }
        }
        
        // add new
        foreach($this->_ee_channels as $channel_id){
            if(!in_array($channel_id, $this->_module_channels)){
                // add channel entry
                $RecordModel = $this->_get_rmdl();
                $RecordModel->channel_id = $channel_id;
                $RecordModel->insert_with_key();
                unset($RecordModel);
            }
        }
        
    }

    /**
     * CP Tabs
     * @access public
     * @return bool
     */
    public function tabs(){
        $tabs = array();
        $tabs['neoseo'] = array(
                                'title_tag' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'meta_description' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),                    
                                'meta_keywords' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'meta_robots_ovid' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'open_graph_url' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'open_graph_type_ovid' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'open_graph_article_author' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'open_graph_site_name' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'open_graph_title' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'open_graph_image' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'open_graph_description' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'facebook_app_id' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'twitter_card_type_ovid' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'twitter_card_url' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'twitter_card_site' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'twitter_card_title' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'twitter_card_image' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    ),
                                'twitter_card_description' => array(
                                                    'visible' => TRUE,
                                                    'collapse' => FALSE,
                                                    'htmlbuttons' => FALSE,
                                                    'width' => '100%'
                                                    )
                                );

        return $tabs;
    }
    
}