<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * channel utility functions - singleton
 * @package     ExpressionEngine
 * @category    Module
 * @author      Greg Crane
 * @link        http://ghcrane.com
 * @version     0.9.5
 *
 */

class Ghc_channel_utilities {

	private static $_instance;
    private $_channelData = array();

	public function __construct(){
    }

	/**
	 * returns either a Ghc_channel_utilities object or a reference if one already exists
     * @access public
	 * @return object $_instance
	 */
	public function get_instance(){
		if (!isset(self::$_instance)) {
			self::$_instance = new Ghc_channel_utilities;
		}
		return self::$_instance;
	}

    /**
     * repopulates _channelData array
     * @access public
     * @return void
     */
    public function reset_channel_data_array(){
        $this->_channelData = array();
        $this->_populate_channel_data();
    }

    /**
     * returns either entire _channelData array, or just a specific channel's data
     * @access public
     * @param $channel_id
     * @return array
     */
    public function get_channel_data_array($channel_id = NULL){
        $this->_populate_channel_data();
        if(!is_null($channel_id)){
            return (key_exists($channel_id, $this->_channelData)) ? $this->_channelData[$channel_id] : FALSE;
        }
        return $this->_channelData;
    }
    
    /**
     * returns field data
     * @access public
     * @param $channel_id
     * @param $field_id
     * @return array
     */
    public function get_field_data_array($channel_id, $field_id){
        $this->_populate_channel_data();
        return $this->_channelData[$channel_id]['fields'][$field_id];
    }

    /**
     * populates _channelData array. using $array[] method instead of array_push() since it's faster
     * @access private
     * @return void
     */
    private function _populate_channel_data() {

        // create api objects
        ee()->api->instantiate('channel_fields');
        ee()->api->instantiate('channel_structure');
        ee()->api->instantiate('channel_categories');

        ///////////////////////////////
        // get channels
        ///////////////////////////////
        $channelsObject = ee()->api_channel_structure->get_channels();
        $channels = $channelsObject->result();

        foreach($channels as $channel){

            ///////////////////////////////
            // get individual channel info
            ///////////////////////////////
            $channelInfoObject  = ee()->api_channel_structure->get_channel_info($channel->channel_id);
            $channelInfoResult  = $channelInfoObject->result();
            $channelInfo        = $channelInfoResult[0];

            $this->_channelData[$channelInfo->channel_id]['channel_id']     = $channelInfo->channel_id;
            $this->_channelData[$channelInfo->channel_id]['channel_name']   = $channelInfo->channel_name;
            $this->_channelData[$channelInfo->channel_id]['channel_title']  = $channelInfo->channel_title;
            $this->_channelData[$channelInfo->channel_id]['total_entries']  = $channelInfo->total_entries;
            $this->_channelData[$channelInfo->channel_id]['status_group']   = $channel->status_group;

            ///////////////////////////////
            // get channel categories
            ///////////////////////////////
            $this->_channelData[$channelInfo->channel_id]['cat_group'] = $channel->cat_group;
            $channelCategories = ee()->api_channel_categories->category_tree($channel->cat_group);
            $this->_channelData[$channelInfo->channel_id]['categories'] = $channelCategories;

            /////////////////////////////////
            // get channel fields
            /////////////////////////////////
            $this->_channelData[$channelInfo->channel_id]['field_group'] = $channel->field_group;

            $channelFields = ee()->channel_model->get_channel_fields($channelInfo->field_group);
            $fieldData = array();

            // loop each field
            foreach($channelFields->result_array() as $row){

                $fieldData[$row['field_id']]['field_id']              = $row['field_id'];
                $fieldData[$row['field_id']]['field_column_name']     = 'field_id_' . $row['field_id'];
                $fieldData[$row['field_id']]['field_type']            = $row['field_type'];
                $fieldData[$row['field_id']]['field_name']            = $row['field_name'];
                $fieldData[$row['field_id']]['field_label']           = $row['field_label'];
                $fieldData[$row['field_id']]['field_instructions']    = $row['field_instructions'];

                if(isset($row['field_settings']) && strlen($row['field_settings'])){
                    $ft_settings = unserialize(base64_decode($row['field_settings']));
                    $ignore = array('field_show_smileys','field_show_glossary','field_show_spellcheck','field_show_formatting_btns','field_show_file_selector','field_show_writemode');
                    foreach($ft_settings as $key=>$value){
                        if(!in_array($key,$ignore)){
                            $fieldData[$row['field_id']]['field_settings'][$key] = $value;
                        }
                    }
                }

                $fieldData[$row['field_id']]['field_maxl']        = $row['field_maxl'];
                $fieldData[$row['field_id']]['field_required']    = $row['field_required'];
                $fieldData[$row['field_id']]['field_search']      = $row['field_search'];
                $fieldData[$row['field_id']]['field_fmt']         = $row['field_fmt'];
                $fieldData[$row['field_id']]['field_order']       = $row['field_order'];
                $fieldData[$row['field_id']]['field_content_type'] = $row['field_content_type'];

                // add relevent fields based on fieldtype
                switch($row['field_type']){
                    case 'select':
                    case 'multi_select':
                    case 'checkboxes':
                    case 'radio':
                        if(isset($row['field_list_items']) && strlen($row['field_list_items'])){
                            $ftli = explode("\n", $row['field_list_items']);
                            $fieldData[$row['field_id']]['field_list_items'] = $ftli;
                        }
                        break;

                    case 'textarea':
                        $fieldData[$row['field_id']]['field_ta_rows'] = $row['field_ta_rows'];
                        break;

                    case 'grid':
                        $fieldData[$row['field_id']]['grid_table'] = 'exp_channel_grid_field_' . $row['field_id'];
                        // get grid columns
                        $columns = ee()->db
                                        ->where('field_id', $row['field_id'])
                                        ->order_by('col_order')
                                        ->get('exp_grid_columns')
                                        ->result();

                        foreach($columns as $column){
                            $fieldData[$row['field_id']]['grid_columns'][$column->col_id]['col_id']         = $column->col_id;
                            $fieldData[$row['field_id']]['grid_columns'][$column->col_id]['column_name']    = 'col_id_' . $column->col_id;
                            $fieldData[$row['field_id']]['grid_columns'][$column->col_id]['field_name']     = $column->col_name;
                            $fieldData[$row['field_id']]['grid_columns'][$column->col_id]['col_type']       = $column->col_type;
                            $fieldData[$row['field_id']]['grid_columns'][$column->col_id]['col_order']      = $column->col_order;
                            $fieldData[$row['field_id']]['grid_columns'][$column->col_id]['col_label']      = $column->col_label;
                            $fieldData[$row['field_id']]['grid_columns'][$column->col_id]['col_instructions'] = $column->col_instructions;
                            $fieldData[$row['field_id']]['grid_columns'][$column->col_id]['col_required']   = $column->col_required;
                            $fieldData[$row['field_id']]['grid_columns'][$column->col_id]['col_search']     = $column->col_search;
                            $fieldData[$row['field_id']]['grid_columns'][$column->col_id]['col_settings']   = json_decode($column->col_settings);

                            if(isset($fieldData[$row['field_name']]['grid_columns'][$column->col_id]['col_settings']->field_list_items)
                                && strlen($fieldData[$row['field_name']]['grid_columns'][$column->col_id]['col_settings']->field_list_items)){
                                $gfli = explode("\n", $fieldData[$row['field_name']]['grid_columns'][$column->col_id]['col_settings']->field_list_items);
                                $fieldData[$row['field_id']]['grid_columns'][$column->col_id]['col_settings']->field_list_items = $gfli;
                            }

                        }
                        break;

                    case 'date':
                    case 'file':
                    case 'text':
                    case 'relationship':
                    case 'structure':
                    case 'wygwam':
                        break;
                }

            }

            $this->_channelData[$channelInfo->channel_id]['fields'] = $fieldData;

        }


        ksort($this->_channelData);

    }

    public function get_child_categories() {

        // create api objects
        ee()->api->instantiate('channel_categories');

        ///////////////////////////////
        // get channel categories
        ///////////////////////////////
        $channelCategories = ee()->api_channel_categories->category_tree(1);

    }

}