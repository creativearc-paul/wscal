<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* @package     ExpressionEngine
* @category    Module
* @author      Greg Crane
* @link        http://ghcrane.com
*/

class Neoseo_channel_vmdl extends Neoseo_vmdl_abstract {

    const DB_TABLE    = 'exp_neoseo_channels';
    const DB_TABLE_PK = 'exp_neoseo_channels.channel_id';
                         
    public function __construct() {
        
        parent::__construct();

        $this->_columnMap = array(
                                 'channel_id'               => $this::CHANNELS_TABLE . '.channel_id',
                                 'created_at'               => $this::CHANNELS_TABLE . '.created_at',
                                 'updated_at'               => $this::CHANNELS_TABLE . '.updated_at',
                                 'display_created_at'       => 'IF(' . $this::CHANNELS_TABLE . '.created_at, date_format(' . $this::CHANNELS_TABLE . '.created_at, "%c-%e-%Y, %l:%i %p"), NULL)',
                                 'display_updated_at'       => 'IF(' . $this::CHANNELS_TABLE . '.updated_at, date_format(' . $this::CHANNELS_TABLE . '.updated_at, "%c-%e-%Y, %l:%i %p"), NULL)',
                                 'manage_channel'           => $this::CHANNELS_TABLE . '.manage_channel',
                                 
                                 'display_manage_channel'   => 'CASE
                                                                    WHEN ' . $this::CHANNELS_TABLE . '.manage_channel = "y" THEN "Yes"
                                                                    ELSE "No"
                                                                END',

                                 'title_tag'                => $this::CHANNELS_TABLE . '.title_tag',
                                 'meta_description'         => $this::CHANNELS_TABLE . '.meta_description',
                                 'meta_keywords'            => $this::CHANNELS_TABLE . '.meta_keywords',
                                 
                                 'meta_robots_ovid'         => 'meta_robots_ovid.option_value',

                                 'open_graph_url'           => $this::CHANNELS_TABLE . '.open_graph_url',
                                 'open_graph_type_ovid'     => 'open_graph_type_ovid.option_value',
                                 'open_graph_article_author'=> $this::CHANNELS_TABLE . '.open_graph_article_author',
                                 'open_graph_site_name'     => $this::CHANNELS_TABLE . '.open_graph_site_name',
                                 'open_graph_title'         => $this::CHANNELS_TABLE . '.open_graph_title',
                                 'open_graph_image'         => $this::CHANNELS_TABLE . '.open_graph_image',
                                 'open_graph_description'   => $this::CHANNELS_TABLE . '.open_graph_description',

                                 'facebook_app_id'          => $this::CHANNELS_TABLE . '.facebook_app_id',

                                 'twitter_card_type_ovid'   => 'twitter_card_type_ovid.option_value',
                                 'twitter_card_url'         => $this::CHANNELS_TABLE . '.twitter_card_url',
                                 'twitter_card_site'        => $this::CHANNELS_TABLE . '.twitter_card_site',
                                 'twitter_card_title'       => $this::CHANNELS_TABLE . '.twitter_card_title',
                                 'twitter_card_image'       => $this::CHANNELS_TABLE . '.twitter_card_image',
                                 'twitter_card_description' => $this::CHANNELS_TABLE . '.twitter_card_description',

                                 'channel_title'             => 'exp_channels.channel_title',
                                 'channel_name'              => 'exp_channels.channel_name'
                                 );

        $this->_join = array(
                         'exp_channels'                         => $this::CHANNELS_TABLE . '.channel_id = exp_channels.channel_id',
                         $this::OPTION_VALUES_TABLE . ' AS meta_robots_ovid' => $this::CHANNELS_TABLE . '.meta_robots_ovid = meta_robots_ovid.option_value_id',
                         $this::OPTION_VALUES_TABLE . ' AS open_graph_type_ovid' => $this::CHANNELS_TABLE . '.open_graph_type_ovid = open_graph_type_ovid.option_value_id',
                         $this::OPTION_VALUES_TABLE . ' AS twitter_card_type_ovid' => $this::CHANNELS_TABLE . '.twitter_card_type_ovid = twitter_card_type_ovid.option_value_id'
                         );

        $this->_date_columns = array(
                                 'created_at' => array( 
                                     'column' => $this::CHANNELS_TABLE . '.created_at',
                                     'format' => 'mysql_datetime'
                                 ),
                                 'updated_at' => array( 
                                     'column' => $this::CHANNELS_TABLE . '.updated_at',
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
        
        ee()->api->instantiate('channel_structure');
        $channelsObject = ee()->api_channel_structure->get_channels();
        $channels = $channelsObject->result();
        
        $returnArray[''] = '';
        
        foreach($channels as $Channel){
            $returnArray[$Channel->channel_id] = $Channel->channel_title;
        }
        return $returnArray;

    }

}