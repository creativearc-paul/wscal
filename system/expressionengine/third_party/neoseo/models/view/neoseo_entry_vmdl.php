<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Neoseo_entry_vmdl extends Neoseo_vmdl_abstract {

    const DB_TABLE    = 'exp_neoseo_entries';
    const DB_TABLE_PK = 'exp_neoseo_entries.entry_id';                               
                                    
    public function __construct() {
        
        parent::__construct();
        
        
        $this->_columnMap = array(
                                    'entry_id'                  => $this::ENTRIES_TABLE . '.entry_id',
                                    'created_at'                => $this::ENTRIES_TABLE . '.created_at',
                                    'updated_at'                => $this::ENTRIES_TABLE . '.updated_at',
                                    'display_created_at'        => 'IF(' . $this::ENTRIES_TABLE . '.created_at, date_format(' . $this::ENTRIES_TABLE . '.created_at, "%c-%e-%Y, %l:%i %p"), NULL)',
                                    'display_updated_at'        => 'IF(' . $this::ENTRIES_TABLE . '.updated_at, date_format(' . $this::ENTRIES_TABLE . '.updated_at, "%c-%e-%Y, %l:%i %p"), NULL)',
                                    'channel_id'                => $this::ENTRIES_TABLE . '.channel_id',
                                    
                                    'title_tag'                 => $this::ENTRIES_TABLE . '.title_tag',
                                    'meta_description'          => $this::ENTRIES_TABLE . '.meta_description',
                                    'meta_keywords'             => $this::ENTRIES_TABLE . '.meta_keywords',
                                    
                                    'meta_robots_ovid'          => 'meta_robots_ovid.option_value',
                                    
                                    'open_graph_url'            => $this::ENTRIES_TABLE . '.open_graph_url',
                                    'open_graph_type_ovid'      => 'open_graph_type_ovid.option_value',
                                    'open_graph_article_author' => $this::ENTRIES_TABLE . '.open_graph_article_author',
                                    'open_graph_site_name'      => $this::ENTRIES_TABLE . '.open_graph_site_name',
                                    'open_graph_title'          => $this::ENTRIES_TABLE . '.open_graph_title',
                                    'open_graph_image'          => $this::ENTRIES_TABLE . '.open_graph_image',
                                    'open_graph_description'    => $this::ENTRIES_TABLE . '.open_graph_description',
                                    
                                    'facebook_app_id'           => $this::ENTRIES_TABLE . '.facebook_app_id',
                                    
                                    'twitter_card_type_ovid'    => 'twitter_card_type_ovid.option_value',
                                    'twitter_card_url'          => $this::ENTRIES_TABLE . '.twitter_card_url',
                                    'twitter_card_site'         => $this::ENTRIES_TABLE . '.twitter_card_site',
                                    'twitter_card_title'        => $this::ENTRIES_TABLE . '.twitter_card_title',
                                    'twitter_card_image'        => $this::ENTRIES_TABLE . '.twitter_card_image',
                                    'twitter_card_description'  => $this::ENTRIES_TABLE . '.twitter_card_description',
                                    
                                    'entry_title'               => 'exp_channel_titles.title',
                                    'entry_url_title'           => 'exp_channel_titles.url_title',
                                    'entry_status'              => 'exp_channel_titles.status',
                                    'channel_title'             => 'exp_channels.channel_title',
                                    'channel_name'              => 'exp_channels.channel_name'
                                    );

        $this->_join = array(
                                'exp_channel_titles'                    => $this::ENTRIES_TABLE . '.entry_id = exp_channel_titles.entry_id',
                                'exp_channels'                          => $this::ENTRIES_TABLE . '.channel_id = exp_channels.channel_id',
                                $this::OPTION_VALUES_TABLE . ' AS meta_robots_ovid' => $this::ENTRIES_TABLE . '.meta_robots_ovid = meta_robots_ovid.option_value_id',
                                $this::OPTION_VALUES_TABLE . ' AS open_graph_type_ovid' => $this::ENTRIES_TABLE . '.open_graph_type_ovid = open_graph_type_ovid.option_value_id',
                                $this::OPTION_VALUES_TABLE . ' AS twitter_card_type_ovid' => $this::ENTRIES_TABLE . '.twitter_card_type_ovid = twitter_card_type_ovid.option_value_id'
                                );
                                
        $this->_date_columns = array(
                                    'created_at' => array( 
                                                        'column' => $this::ENTRIES_TABLE . '.created_at',
                                                        'format' => 'mysql_datetime'
                                                        ),
                                    'updated_at' => array( 
                                                        'column' => $this::ENTRIES_TABLE . '.updated_at',
                                                        'format' => 'mysql_datetime'
                                                        )
                                    );
                                
    }
    
    /**
    * get form options
    * @access public
    * @return array
    */
    public function get_form_options(){
        return array(''=>'');
    }

        
    public function get_ee_entry_title($entry_id){
        ee()->db->select('exp_channel_titles.title', FALSE);
        ee()->db->where('exp_channel_titles.entry_id', $entry_id);
        $query = ee()->db->get('exp_channel_titles');
        $result = $query->row();
        return $result->title;
    }
    
}