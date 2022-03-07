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

class Neoseo_upd {

	public $version = '2.1.8';
    const MOD_CLASS = 'Neoseo';

	function __construct() {
    }

	/**
	 * Module Installer
	 * @access    public
	 * @return    bool
	 */
	public function install() {

		ee()->load->dbforge();

		$data = array(
					'module_name'       => $this::MOD_CLASS,
					'module_version'    => $this->version,
					'has_cp_backend'    => 'y',
					'has_publish_fields'=> 'y'
					);

		ee()->db->insert('modules', $data);

        ///////////////////
        // DB Tables
        ///////////////////
        // entry meta
        $fields = array(
                        'entry_id'                  => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE),
                        'created_at'                => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00'),
                        'updated_at'                => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00'),
                        'channel_id'                => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE),
                        
                        'title_tag'                 => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'meta_description'          => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'meta_keywords'             => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        
                        'meta_robots_ovid'          => array('type' => 'int', 'constraint' => '4', 'unsigned' => TRUE, 'null' => FALSE, 'default' => 1),

                        'open_graph_url'            => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'open_graph_type_ovid'      => array('type' => 'int', 'constraint' => '4', 'unsigned' => TRUE, 'null' => FALSE, 'default' => 5),
                        'open_graph_article_author' => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'open_graph_site_name'      => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'open_graph_title'          => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'open_graph_image'          => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'open_graph_description'    => array('type' => 'varchar', 'constraint' => '600', 'default' => ''),
                        
                        'facebook_app_id'           => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        
                        'twitter_card_type_ovid'    => array('type' => 'int', 'constraint' => '4', 'unsigned' => TRUE, 'null' => FALSE, 'default' => 7),
                        'twitter_card_url'          => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'twitter_card_site'         => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'twitter_card_title'        => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'twitter_card_image'        => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'twitter_card_description'  => array('type' => 'varchar', 'constraint' => '600', 'default' => '')
                        );
        ee()->dbforge->add_field($fields);
        ee()->dbforge->add_key('entry_id', TRUE);
        ee()->dbforge->create_table('neoseo_entries', TRUE);
        unset($fields);
        
        // channel settings
        $fields = array(
                        'channel_id'                => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE),
                        'created_at'                => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00'),
                        'updated_at'                => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00'),
                        'manage_channel'            => array('type' => 'char', 'constraint' => '1', 'null' => FALSE, 'default' => 'n'),
                        
                        'default_title_field'       => array('type' => 'int', 'constraint' => '4', 'unsigned' => TRUE, 'null' => FALSE, 'default' => 0),
                        
                        'title_tag'                 => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'meta_description'          => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'meta_keywords'             => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        
                        'meta_robots_ovid'          => array('type' => 'int', 'constraint' => '4', 'unsigned' => TRUE, 'null' => FALSE, 'default' => 1),

                        'open_graph_url'            => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'open_graph_type_ovid'      => array('type' => 'int', 'constraint' => '4', 'unsigned' => TRUE, 'null' => FALSE, 'default' => 5),
                        'open_graph_article_author' => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'open_graph_site_name'      => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'open_graph_title'          => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'open_graph_image'          => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'open_graph_description'    => array('type' => 'varchar', 'constraint' => '600', 'default' => ''),
                        
                        'facebook_app_id'           => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        
                        'twitter_card_type_ovid'    => array('type' => 'int', 'constraint' => '4', 'unsigned' => TRUE, 'null' => FALSE, 'default' => 7),
                        'twitter_card_url'          => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'twitter_card_site'         => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'twitter_card_title'        => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'twitter_card_image'        => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'twitter_card_description'  => array('type' => 'varchar', 'constraint' => '600', 'default' => '')
                        );
        ee()->dbforge->add_field($fields);
        ee()->dbforge->add_key('channel_id', TRUE);
        ee()->dbforge->create_table('neoseo_channels', TRUE);
        unset($fields);

        // option values
        $fields = array(
                        'option_value_id'   => array('type' => 'int', 'constraint' => '10', 'unsigned' => TRUE, 'auto_increment' => TRUE),
                        'created_at'        => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00'),
                        'updated_at'        => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00'),
                        'option_group'      => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'sort_order'        => array('type' => 'int', 'constraint' => '4', 'unsigned' => TRUE, 'default' => 0),
                        'option_value'      => array('type' => 'varchar', 'constraint' => '255', 'default' => ''),
                        'option_label'      => array('type' => 'varchar', 'constraint' => '255', 'default' => '')
                        );
        ee()->dbforge->add_field($fields);
        ee()->dbforge->add_key('option_value_id', TRUE);
        ee()->dbforge->create_table('neoseo_option_values', TRUE);
        unset($fields);
        
       // add option values
        $data = array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'option_group' => 'meta_robots',
                        'option_value' => 'index, follow',
                        'option_label' => 'Index, Follow',
                        );
        ee()->db->insert('neoseo_option_values', $data);
        unset($data);
        
        $data = array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'option_group' => 'meta_robots',
                        'option_value' => 'index, nofollow',
                        'option_label' => 'Index, No Follow',
                        );
        ee()->db->insert('neoseo_option_values', $data);
        unset($data);
        
        $data = array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'option_group' => 'meta_robots',
                        'option_value' => 'noindex, follow',
                        'option_label' => 'No Index, Follow',
                        );
        ee()->db->insert('neoseo_option_values', $data);
        unset($data);
        
        $data = array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'option_group' => 'meta_robots',
                        'option_value' => 'noindex, nofollow',
                        'option_label' => 'No Index, No Follow',
                        );
        ee()->db->insert('neoseo_option_values', $data);
        unset($data);
        
        $data = array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'option_group' => 'open_graph_type',
                        'option_value' => 'website',
                        'option_label' => 'Website',
                        );
        ee()->db->insert('neoseo_option_values', $data);
        unset($data);
        
        $data = array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'option_group' => 'open_graph_type',
                        'option_value' => 'article',
                        'option_label' => 'Article',
                        );
        ee()->db->insert('neoseo_option_values', $data);
        unset($data);
        
        $data = array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'option_group' => 'twitter_card_type',
                        'option_value' => 'summary',
                        'option_label' => 'Summary Card',
                        );
        ee()->db->insert('neoseo_option_values', $data);
        unset($data);
        
        $data = array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'option_group' => 'twitter_card_type',
                        'option_value' => 'summary_large_image',
                        'option_label' => 'Summary Card with Large Image',
                        );
        ee()->db->insert('neoseo_option_values', $data);
        unset($data);

        ///////////////////
        // Add Publish Tabs
        ///////////////////
        //ee()->load->library('layout');
        //ee()->layout->add_layout_tabs($this->tabs(), 'neoseo');

		return TRUE;

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

	/**
	 * Module Uninstaller
	 * @access public
	 * @return bool
	 */
	public function uninstall() {

        ee()->load->dbforge();
        ee()->db->select('module_id');
        $query = ee()->db->get_where('modules', array('module_name' => $this::MOD_CLASS));

        ee()->db->where('module_name', $this::MOD_CLASS);
        ee()->db->delete('modules');

        ee()->db->where('module_id', $query->row('module_id'));
        ee()->db->delete('module_member_groups');

        ee()->db->where('class', $this::MOD_CLASS);
        ee()->db->delete('actions');

        ee()->dbforge->drop_table('neoseo_entries');
        ee()->dbforge->drop_table('neoseo_channels');
        ee()->dbforge->drop_table('neoseo_option_values');

        ee()->load->library('layout');
        ee()->layout->delete_layout_tabs($this->tabs(), 'neoseo');

		return TRUE;

	}

	/**
	 * Module Updater
	 * @access public
	 * @return bool
	 */
     public function update($current = '') {

         // don't do anything if the version hasn't changed
         if($current == $this->version) {  
             return FALSE;
         }

         // the version property has  a version higher than current version in db
         // this means the module is being updated.
         if($current < $this->version) {

             ee()->load->library('layout');

             // Blow out the old tabs
             ee()->layout->delete_layout_tabs($this->tabs()); 

             // get channel states and update tabs
             ee()->db->select('channel_id, manage_channel');
             $query = ee()->db->get_where('exp_neoseo_channels', array('manage_channel' => 'y'));
             $results = $query->result();

             foreach($results as $Row) {
                ee()->layout->add_layout_tabs($this->tabs(), 'neoseo', $Row->channel_id);
             }

         }
         return TRUE;
     }
}