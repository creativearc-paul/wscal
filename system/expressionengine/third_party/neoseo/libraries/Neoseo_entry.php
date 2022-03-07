<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 */

class Neoseo_entry extends Neoseo_entity_abstract{

    const MOD_NAME = 'neoseo';
    
    /**
    *  define some constants to make life easier
    */
    const LIB_CLASS     = 'neoseo_entry';
    const DISPLAY_NAME  = 'Entry Metadata';
                    
    // main record model
    protected $_record_model_name = 'Neoseo_entry_rmdl';
    
    // main view model
    protected $_view_model_name = 'Neoseo_entry_vmdl';

    /**
    * default column to sort listing view datatable
    * @var array
    */                                         
    protected $_default_sort = array('entry_id' => 'desc');
    
    /**
    * columns used in our display table, keys are view model column aliases so we have sorting. The key column_name may not be the exact content of the 
    * table cell, for example we may wish to sort by person_last_name yet display $Result->person_full_name or a composite of multiple data items in table cell
    * @var array
    */
    protected $_datatable_columns = array(
                                        'entry_id'          => array('header' => array('data' => 'Entry ID', 'style' => 'width:85px;')),
                                        'channel_title'     => array('header' => array('data' => 'Channel', 'style' => 'width:80px;')),
                                        'entry_title'       => array('header' => array('data' => 'Entry Title', 'style' => 'width:350px;')),
                                        'title_tag'         => array('header' => array('data' => 'Title Tag')),
                                        'meta_description'  => array('header' => array('data' => 'Meta Description')),
                                        'updated_at'        => array('header' => array('data' => 'Meta Last Modified', 'style' => 'width:150px;text-align:center;')),
                                        );
    
    /**
    * define column aliases that represent date related items so we know how to convert when saving or displaying
    * @var array 'model_column_alias' => 'mysql_format', date, time, datetime, timestamp, unixtime, etc.
    */                                    
    protected $_date_fields = array(
                                    'created_at' => 'datetime',
                                    'updated_at' => 'datetime',
                                    );
                                    
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

    private $_tags = array(
                            'title_tag'=>'',
                            'meta_description'=>'',
                            'meta_keywords'=>'',
                            
                            'meta_robots_ovid'=>'',
                            
                            'open_graph_url'=>'',
                            'open_graph_type_ovid'=>'website',
                            'open_graph_article_author'=>'',
                            'open_graph_site_name'=>'',
                            'open_graph_title'=>'',
                            'open_graph_image'=>'',
                            'open_graph_description'=>'',
                            
                            'facebook_app_id'=>'',
                            
                            'twitter_card_type_ovid'=>'summary',
                            'twitter_card_url'=>'',
                            'twitter_card_site'=>'',
                            'twitter_card_title'=>'',
                            'twitter_card_image'=>'',
                            'twitter_card_description'=>''
                            );

    protected $_admin_form = 'form_entry';
    
    /**
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function __construct() {

        parent::__construct();
        
        /**
        * define listing filters
        */
        $KeywordFilter = new stdClass();
        $KeywordFilter->options = array(
                                        'entry_title'       => 'Entry Title',
                                        'title_tag'         => 'Title Tag',
                                        'meta_description'  => 'Meta Description'
                                        );
        $this->_listing_filters['keyword_filter'] = $KeywordFilter;
        
        $SelectFilter = new stdClass();
        $SelectFilter->label = 'Channel';
        $SelectFilter->column = 'channel_id';
        $SelectFilter->options = $this->SelectOptions->channels;
        $this->_listing_filters['select_filters'][] = $SelectFilter;
        
        $DateRangeFilter = new stdClass();
        $DateRangeFilter->options = array(
                                        'created_at' => 'Date Created',
                                        'updated_at' => 'Date Modified'
                                        );
        $this->_listing_filters['date_range_filter'] = $DateRangeFilter;
        
        // export request
        $this->_export = $this->_lib_get_url . AMP . 'libm=export';
        
        $this->_listing_buttons = array(
                                        array(
                                            'url'   => $this->_export,
                                            'label' => 'Export Entry Metadata'
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
        
        $returnRows = function($Row) use(&$tableRows){
            // truncate desc for display
            if(strlen($Row->meta_description) > 50){
                $Row->meta_description = wordwrap($Row->meta_description, 50);
                $Row->meta_description = substr($Row->meta_description, 0, strpos($Row->meta_description, "\n")) . '&hellip;';
            }

            // add db result $Row to array used in constructing listing table
            $tableRows[] = array(
                                'entry_id'          => array('data' => $Row->entry_id, 'style' => 'text-align:center;'),
                                'channel_title'     => array('data' => $Row->channel_title),
                                'entry_title'       => array('data' => '<a href="' . $this->_edit . AMP . 'pk=' . $Row->entry_id . '">' . $Row->entry_title . '</a>'),
                                'title_tag'         => array('data' => $Row->title_tag),
                                'meta_description'  => array('data' => $Row->meta_description),
                                'updated_at'        => array('data' => $Row->display_updated_at, 'style' => 'text-align:center;'),
                                );                
        };
        
        // load the main view model for this listing table and get results
        $ViewModel = $this->_get_vmdl();
        $results = $this->get_datatable_results($ViewModel, $state, $params);
        $tableRows = array();
        array_map($returnRows, $results); 

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
            
        // get channel defaults
        ee()->load->model('view/Neoseo_channel_vmdl');
        $ChannelModel = new Neoseo_channel_vmdl();
        $params = array('where' => array('channel_id' => $RecordModel->channel_id));
        $result = $ChannelModel->get_records(1, 0, $params);
        $channelDefaults = (count($result) > 0) ? $result[key($result)] : new stdClass();
                
        $viewVars = array();
        $viewVars['pk']                 = ee()->input->get_post('pk', TRUE);
        $viewVars['form_action_url']    = $this->_action_save;
        $viewVars['return_uri']         = base64_encode($this->_return_uri); // where "back" button goes or form redirects when saved
        $viewVars['Record']             = $RecordModel;
        $viewVars['RecordView']         = (isset($RecordView)) ? $RecordView : new stdClass();
        $viewVars['ChannelDefaults']    = $channelDefaults;
        $viewVars['SelectOptions']      = $this->SelectOptions; // arrays for populating each select fields in view
        $viewVars['Checkboxes']         = $this->Checkboxes; // arrays for populating checkbox groups
        if($_field_data){
            $viewVars['_field_data']    = $_field_data; // needed for repopulating form values
        }
        
        // set appropriate cp page title
        ee()->view->cp_page_title = 'Editing Entry: ' . $RecordView->entry_id . ' &ndash; ' . $RecordView->entry_title;

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
        ee()->form_validation->set_rules($rules['neoseo_entry']);
        ee()->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        if(ee()->form_validation->run() == FALSE){ // validation failed, have to reload form
            return $this->_get_form_view($RecordModel, ee()->form_validation->_field_data);
        }

        ///////////////// we passed validation, onward! ///////////////////
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
     * publish tabs
     * @access public
     * @param int $channel_id
     * @param int $entry_id
     * @return array
     * @author Greg Crane
     */
    public function publish_tabs($channel_id, $entry_id){

        $RecordModel = ($entry_id != 0) ? $this->_get_rmdl($entry_id) : $this->_get_rmdl();
        
        $SelectOptions = $this->SelectOptions;
        
        $settings = array();
        $settings[] = array(
                            'field_id'              => 'title_tag',
                            'field_label'           => 'Title Tag',
                            'field_required'        => 'y',
                            'field_data'            => $RecordModel->title_tag,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'Shown in the browser title bar, user bookmarks, and search engine results.',
                            'field_maxl'            => 70,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'meta_description',
                            'field_label'           => 'Meta Description',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->meta_description,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'A brief description of the page that may be presented in search results.',
                            'field_maxl'            => 160,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'meta_keywords',
                            'field_label'           => 'Meta Keywords',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->meta_keywords,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'Legacy tag. Google will ignore.',
                            'field_maxl'            => 150,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'meta_robots_ovid',
                            'field_label'           => 'Meta Robots',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->meta_robots_ovid,
                            'field_list_items'      => $SelectOptions->meta_robots_ovid,
                            'field_fmt'             => '',
                            'field_instructions'    => 'Meta tag that tells robots to index the page content, and/or scan for links to follow.',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'select'
                            );
        $settings[] = array(
                            'field_id'              => 'open_graph_url',
                            'field_label'           => 'Open Graph URL',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->open_graph_url,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'Leave blank to default to current page URL. Will usually only be used if the current URL is not the canonical version of the page.',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'open_graph_type_ovid',
                            'field_label'           => 'Open Graph Type',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->open_graph_type_ovid,
                            'field_list_items'      => $SelectOptions->open_graph_type_ovid,
                            'field_fmt'             => '',
                            'field_instructions'    => 'Type of content.',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'select'
                            );
        $settings[] = array(
                            'field_id'              => 'open_graph_article_author',
                            'field_label'           => 'Article Author',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->open_graph_article_author,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'URL for the Facebook profile or Facebook page of the article author (e.g. https://www.facebook.com/my_page).',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'open_graph_site_name',
                            'field_label'           => 'Open Graph Site Name',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->open_graph_site_name,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'The name of your website (not the URL).',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'open_graph_title',
                            'field_label'           => 'Open Graph Title',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->open_graph_title,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'Title of page/article as shown in Facebook (exclude any branding).',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'open_graph_image',
                            'field_label'           => 'Open Graph Image',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->open_graph_image,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'Image shown in Facebook. Facebook suggests that you use an image of at least 1200x630 pixels.',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'file'
                            //'field_type'            => (array_key_exists('assets', ee()->addons->get_installed())) ? 'assets' : 'file'
                            );
        $settings[] = array(
                            'field_id'              => 'open_graph_description',
                            'field_label'           => 'Open Graph Description',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->open_graph_description,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'Two or three sentences describing content.',
                            'field_maxl'            => 600,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'facebook_app_id',
                            'field_label'           => 'Facebook App ID',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->facebook_app_id,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'Used for Facebook Insights. Unique ID that identifies your domain to Facebook.',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'twitter_card_type_ovid',
                            'field_label'           => 'Twitter Card Type',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->twitter_card_type_ovid,
                            'field_list_items'      => $SelectOptions->twitter_card_type_ovid,
                            'field_fmt'             => '',
                            'field_instructions'    => '',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'select'
                            );
        $settings[] = array(
                            'field_id'              => 'twitter_card_url',
                            'field_label'           => 'Twitter Card URL',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->twitter_card_url,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'Leave blank to default to current page URL. Will usually only be used if the current URL is not the canonical version of the page.',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'twitter_card_site',
                            'field_label'           => 'Twitter @username',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->twitter_card_site,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'The Twitter @username the card should be attributed to. Required for Twitter Card analytics.',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'twitter_card_title',
                            'field_label'           => 'Twitter Card Title',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->twitter_card_title,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'Title of item for Twitter Card.',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );
        $settings[] = array(
                            'field_id'              => 'twitter_card_image',
                            'field_label'           => 'Twitter Card Image',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->twitter_card_image,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'Unique image representing the content of the page. Do not use a generic image such as your website logo, author photo, or other image that spans multiple pages. The image must be a minimum size of 120px by 120px and must be less than 1MB in file size.',
                            'field_maxl'            => 255,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'file'
                            //'field_type'            => (array_key_exists('assets', ee()->addons->get_installed())) ? 'assets' : 'file'
                            );                    
        $settings[] = array(
                            'field_id'              => 'twitter_card_description',
                            'field_label'           => 'Twitter Card Description',
                            'field_required'        => 'n',
                            'field_data'            => $RecordModel->twitter_card_description,
                            'field_list_items'      => '',
                            'field_fmt'             => '',
                            'field_instructions'    => 'A description that concisely summarizes the content of the page, as appropriate for presentation within a Tweet. Do not re-use the title text as the description, or use this field to describe the general services provided by the website.',
                            'field_maxl'            => 600,
                            'field_show_fmt'        => 'n',
                            'field_fmt_options'     => array(),
                            'field_pre_populate'    => 'n',
                            'field_text_direction'  => 'ltr',
                            'field_type'            => 'text'
                            );

        return $settings;
    }
    
    /**
     * save tab data
     * @access public
     * @param array $entries
     * @return void
     * @author Greg Crane
     */
    public function publish_data_db($entries){

        $data = $entries['data']['revision_post'];

        $neoseo_entry = array(
                                'entry_id'                  => $entries['entry_id'],
                                'channel_id'                => $data['channel_id'],
                                
                                'title_tag'                 => $data['neoseo__title_tag'],
                                'meta_description'          => $data['neoseo__meta_description'],
                                'meta_keywords'             => $data['neoseo__meta_keywords'],
                                
                                'meta_robots_ovid'          => $data['neoseo__meta_robots_ovid'],
                                
                                'open_graph_url'            => $data['neoseo__open_graph_url'],
                                'open_graph_type_ovid'      => $data['neoseo__open_graph_type_ovid'],
                                'open_graph_article_author' => $data['neoseo__open_graph_article_author'],
                                'open_graph_site_name'      => $data['neoseo__open_graph_site_name'],
                                'open_graph_title'          => $data['neoseo__open_graph_title'],
                                'open_graph_image'          => $data['neoseo__open_graph_image'],
                                'open_graph_description'    => $data['neoseo__open_graph_description'],
                                
                                'facebook_app_id'           => $data['neoseo__facebook_app_id'],
                                
                                'twitter_card_type_ovid'    => $data['neoseo__twitter_card_type_ovid'],
                                'twitter_card_url'          => $data['neoseo__twitter_card_url'],
                                'twitter_card_site'         => $data['neoseo__twitter_card_site'],
                                'twitter_card_title'        => $data['neoseo__twitter_card_title'],
                                'twitter_card_image'        => $data['neoseo__twitter_card_image'],
                                'twitter_card_description'  => $data['neoseo__twitter_card_description']
                                );

        // load model
        ee()->load->model('record/Neoseo_entry_rmdl');

        // create new record model
        $RecordModel = new Neoseo_entry_rmdl();
        
        if(!$RecordModel->load((int)$entries['entry_id'])){
            $RecordModel->populate($neoseo_entry);
            $RecordModel->insert_with_key();
        } else {
            $RecordModel->populate($neoseo_entry);
            $RecordModel->update();
        }                    

        return TRUE;

    }
    
    /**
     * construct_csv
     * @access public
     * @return void
     * @author Greg Crane
     */
    public function export() {

        ee()->load->model('export/' . ucfirst($this::MOD_NAME) . '_export_entries_model');
        $expMdl = $this::MOD_NAME . '_export_entries_model';
        $ExportModel = new $expMdl();
        $data = $ExportModel->get_data();

        // load download helper
        ee()->load->helper('download');
        force_download('entries_export_' . date('n-j-Y_gi_a') . '.csv', $data);
        
        exit();

    }

    /**
     * remove channel entries
     * @access public
     * @param int $channel_id
     * @return void
     * @author Greg Crane
     */
    public function remove_channel_entries($channel_id){

        ee()->load->model('record/Neoseo_entry_rmdl');
        $RecordModel = new Neoseo_entry_rmdl();
        $where = array('channel_id' => $channel_id);
        $RecordModel->delete_where($where);
        unset($RecordModel);

    }
    
    /**
     * add default channel entries
     * @access public
     * @param int $channel_id
     * @return void
     * @author Greg Crane
     */
    public function add_channel_entries($channel_id){
        /* 
        // load model
        ee()->load->model('record/Neoseo_entry_rmdl');
        $RecordModel = new Neoseo_entry_rmdl();
        */
    }
    
    //////////////////////////
    // get meta tags tags
    //////////////////////////

    /**
     * construct tags for entry
     * @access public
     * @param string $entry_id
     * @param string $requested_tags
     * @return string 
     * @author Greg Crane
     */
    public function meta_tags($entry_id, $requested_tags = NULL){

        $EntryModel = $this->_get_vmdl();
        
        $params = array('where' => array('entry_id' => $entry_id));
        $result = $EntryModel->get_records(1, 0, $params);
        
        if(count($result) > 0){
            $entryData = $result[key($result)];
        } else {
            return '<!-- missing NeoSEO entry -->';
        }

        // load entry title so we have that at the very least
        $this->_tags['title_tag'] = $EntryModel->get_ee_entry_title($entry_id);

        /**
        *  LOAD CHANNEL DEFAULTS FOR ENTRY
        */
        ee()->load->model('view/Neoseo_channel_vmdl');
        $ChannelModel = new Neoseo_channel_vmdl();
        $cparams = array('where' => array('channel_id' => $entryData->channel_id));
        $cresult = $ChannelModel->get_records(1, 0, $cparams);
        
        if(count($cresult) > 0){
            $channelData = $cresult[key($cresult)];
        } else {
            return '<!-- missing NeoSEO channel -->';
        }
        
        foreach($this->_tags as $tag=>$value){
            if(isset($channelData->$tag) && $channelData->$tag != ''){
                $this->_tags[$tag] = $channelData->$tag;
            }
        }

        /**
        * NOW LOAD ENTRY VALUES
        */
        foreach($this->_tags as $tag=>$value){
            if(isset($entryData->$tag) && $entryData->$tag != ''){
                $this->_tags[$tag] = $entryData->$tag;
            }
        }

        // only get requested tags if needed
        if(!is_null($requested_tags)){
            $requested = explode('|', $requested_tags);
            // make sure we always have title_tag
            $requested[] = 'title_tag';
            foreach($this->_tags as $tag=>$value){
                if(!in_array($tag, $requested)){
                    unset($this->_tags[$tag]);
                }
            }
        }
        
        // drop all empty tags
        foreach($this->_tags as $tag=>$value){
            switch($tag){
                case 'open_graph_url': 
                    if($value == ''){
                        $this->_tags[$tag] = ee()->functions->fetch_current_uri();
                    }
                    break;
                default:    
                    if($value == ''){
                        unset($this->_tags[$tag]);
                    }
                    break;
            }
        }
        
        // parse file paths
        //ee()->load->library('typography');
        //ee()->typography->initialize();
        if(isset($this->_tags['open_graph_image'])){
            $this->_tags['open_graph_image'] = ee()->typography->parse_file_paths($this->_tags['open_graph_image']);
            // check for full path
            if(strpos($this->_tags['open_graph_image'], 'http://') === false && strpos($this->_tags['open_graph_image'], 'https://') === false) {
                $this->_tags['open_graph_image'] = reduce_double_slashes(ee()->config->config['site_url'] . $this->_tags['open_graph_image']);
            }
        }
        if(isset($this->_tags['twitter_card_image'])){
            $this->_tags['twitter_card_image'] = ee()->typography->parse_file_paths($this->_tags['twitter_card_image']);
            // check for full path
            if(strpos($this->_tags['twitter_card_image'], 'http://') === false && strpos($this->_tags['twitter_card_image'], 'https://') === false) {
                $this->_tags['twitter_card_image'] = reduce_double_slashes(ee()->config->config['site_url'] . $this->_tags['twitter_card_image']);
            }
        }

        $viewVars = array();
        $viewVars['tags'] = $this->_tags;
        return ee()->load->view('/public/display/meta_tags', $viewVars, TRUE);

    }
    
}