<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
include_once('eeharbor.php');

// load dependencies
if (! defined('PATH_THIRD')) define('PATH_THIRD', EE_APPPATH.'third_party/');

/**
 * Assets Update
 *
 * @package   Assets
 * @author    EEHarbor <help@eeharbor.com>
 * @copyright Copyright (c) 2016 EEHarbor
 */
class Assets_upd
{
	var $version;

	/**
	 * Constructor
	 */
	function __construct($switch = TRUE)
	{
		// -------------------------------------------
		//  Make a local reference to the EE super object
		// -------------------------------------------
		$this->eeharbor = new \assets\EEHarbor;
		$this->version = $this->eeharbor->getConfig('version');

		require_once PATH_THIRD . 'assets/helper.php';
	}

	/**
	 * Install
	 */
	function install()
	{
		ee()->load->dbforge();

		// -------------------------------------------
		//  Add row to exp_modules
		// -------------------------------------------

		ee()->db->insert('modules', array(
			'module_name'        => $this->eeharbor->getConfig('name'),
			'module_version'     => $this->eeharbor->getConfig('version'),
			'has_cp_backend'     => 'y',
			'has_publish_fields' => 'n'
		));

		// -------------------------------------------
		//  Add rows to exp_actions
		// -------------------------------------------

		// file manager actions
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'upload_file'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_files_view_by_folders'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_props'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'save_props'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_ordered_files_view'));

		// Indexing actions
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_session_id'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'start_index'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'perform_index'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'finish_index'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_s3_buckets'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_gc_buckets'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_rs_regions'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_rs_containers'));

		// folder/file CRUD actions
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'move_folder'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'rename_folder'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'create_folder'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'delete_folder'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'view_file'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'move_file'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'delete_file'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'view_thumbnail'));

		// field actions
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'build_sheet'));
		ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_selected_files'));

		// -------------------------------------------
		//  Create the exp_assets table
		// -------------------------------------------

		$fields = array(
			'file_id'			=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE),
			'folder_id'			=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE, 'null' => FALSE),
			'source_type'		=> array('type' => 'varchar', 'constraint' => 2, 'null' => FALSE, 'default' => 'ee'),
			'source_id'			=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
			'filedir_id'		=> array('type' => 'int', 'constraint' => 4, 'unsigned' => TRUE),
			'file_name'			=> array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE),
			'title'				=> array('type' => 'varchar', 'constraint' => 100),
			'date'				=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE, 'null' => TRUE),
			'alt_text'			=> array('type' => 'tinytext'),
			'caption'			=> array('type' => 'tinytext'),
			'author'			=> array('type' => 'tinytext'),
			'`desc`'			=> array('type' => 'text'),
			'location'			=> array('type' => 'tinytext'),
			'keywords'			=> array('type' => 'text'),
			'date_modified'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
			'kind'				=> array('type' => 'varchar', 'constraint' => 5),
			'width'				=> array('type' => 'int', 'constraint' => 2),
			'height'			=> array('type' => 'int', 'constraint' => 2),
			'size'				=> array('type' => 'int', 'constraint' => 3),
			'search_keywords'	=> array('type' => 'text')
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('file_id', TRUE);
		ee()->dbforge->create_table('assets_files');

		ee()->db->query('ALTER TABLE exp_assets_files ADD UNIQUE unq_folder_id__file_name (folder_id, file_name)');

		// -------------------------------------------
		//  Create the exp_assets_selections table
		// -------------------------------------------

		$fields = array(
			'file_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
			'entry_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
			'field_id'		=> array('type' => 'int', 'constraint' => 6, 'unsigned' => TRUE),
			'col_id'		=> array('type' => 'int', 'constraint' => 6, 'unsigned' => TRUE),
			'row_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
			'var_id'		=> array('type' => 'int', 'constraint' => 6, 'unsigned' => TRUE),
			'element_id'    => array('type' => 'varchar', 'constraint' => 255, 'null' => TRUE),
			'content_type'  => array('type' => 'varchar', 'constraint' => 255, 'null' => TRUE),
			'sort_order'	=> array('type' => 'int', 'constraint' => 4, 'unsigned' => TRUE),
			'is_draft'  	=> array('type' => 'TINYINT', 'constraint' => '1', 'unsigned' => TRUE, 'default' => 0)
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('file_id');
		ee()->dbforge->add_key('entry_id');
		ee()->dbforge->add_key('field_id');
		ee()->dbforge->add_key('col_id');
		ee()->dbforge->add_key('row_id');
		ee()->dbforge->add_key('var_id');
		ee()->dbforge->create_table('assets_selections');

		// folder structure
		$fields = array(
			'folder_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE),
			'source_type'	=> array('type' => 'varchar', 'constraint' => 2, 'null' => FALSE, 'default' => 'ee'),
			'folder_name'	=> array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE),
			'full_path'		=> array('type' => 'varchar', 'constraint' => 255),
			'parent_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE, 'null' => TRUE),
			'source_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
			'filedir_id'	=> array('type' => 'int', 'constraint' => 4, 'unsigned' => TRUE),
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('folder_id', true);
		ee()->dbforge->create_table('assets_folders');

		ee()->db->query('ALTER TABLE exp_assets_folders ADD UNIQUE unq_source_type__source_id__filedir_id__parent_id__folder_name (`source_type`, `source_id`, `filedir_id`, `parent_id`, `folder_name`)');
		ee()->db->query('ALTER TABLE exp_assets_folders ADD UNIQUE unq_source_type__source_id__filedir_id__full_path (`source_type`, `source_id`, `filedir_id`, `full_path`)');

		// source information
		$fields = array(
			'source_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE),
			'source_type'	=> array('type' => 'varchar', 'constraint' => 2, 'null' => FALSE, 'default' => 's3'),
			'name'			=> array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE, 'default' => ''),
			'settings'		=> array('type' => 'text', 'null' => FALSE)
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('source_id', true);
		ee()->dbforge->create_table('assets_sources');

		// table for temporary data during indexing
		$fields = array(
			'session_id'	=> array('type' => 'char', 'constraint' => 36),
			'source_type'	=> array('type' => 'varchar', 'constraint' => 2, 'null' => FALSE, 'default' => 'ee'),
			'source_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
			'offset'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
			'uri'			=> array('type' => 'varchar', 'constraint' => 255),
			'filesize'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
			'type'			=> array('type' => 'enum', 'constraint' => "'file','folder'"),
			'record_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE)
		);
		ee()->dbforge->add_field($fields);
		ee()->dbforge->create_table('assets_index_data');
		ee()->db->query('ALTER TABLE `exp_assets_index_data` ADD UNIQUE unq__session_id__source_type__source_id__offset (`session_id`, `source_type`, `source_id`, `offset`)');

		$fields = array(
			'connection_key' => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE, 'required' => TRUE),
			'token'	         => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE, 'required' => TRUE),
			'storage_url'    => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE, 'required' => TRUE),
			'cdn_url'        => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE, 'required' => TRUE)
		);

		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('connection_key', true);
		ee()->dbforge->create_table('assets_rackspace_access');

		return TRUE;
	}

	/**
	 * Update
	 */
	function update($current = '')
	{
		if ($current == $this->version)
		{
			return FALSE;
		}

		// -------------------------------------------
		//  Require Assets 1.x => 2.x to take place from the module unless DevDemon Updater is running the show
		// -------------------------------------------

		if (version_compare($current, '2.0b2', '<'))
		{
			// Prevent the EE update wizard from running this
			if (get_class($this->EE) == 'Wizard')
			{
				return FALSE;
			}

			// is this DevDemon Updater?
			if (
				ee()->input->get('C') == 'addons_modules' &&
				ee()->input->get('M') == 'show_module_cp' &&
				ee()->input->get('module') == 'updater' &&
				ee()->input->get('method') == 'ajax_router' &&
				ee()->input->get('task') == 'addon_install'
			)
			{
				// make sure they're running Updater 3.1.6 or later, which checks database_backup_required()
				$version = ee()->db->select('module_version')->where('module_name', 'Updater')->get('modules')->row('module_version');
				if (version_compare($version, '3.1.6', '<'))
				{
					ee()->lang->loadfile('assets');
					exit(lang('updater_316_required'));
				}
			}
			else
			{
				// is this an MCP index request?
				$mcp_index = (
					ee()->input->get('C') == 'addons_modules' &&
					ee()->input->get('M') == 'show_module_cp' &&
					ee()->input->get('module') == 'assets' &&
					(($method = ee()->input->get('method')) === FALSE || $method == 'index')
				);

				if (!$mcp_index || ee()->input->get('goforth') != 'y')
				{
					if ($mcp_index)
					{
						// let the MCP know to display the DB backup message
						ee()->session->cache['assets']['show_dbbackup'] = TRUE;
					}
					else
					{
						// redirect to the MCP index
						ee()->functions->redirect($this->eeharbor->moduleURL('assets'));
					}

					// cancel the update
					return FALSE;
				}
			}
		}

		// -------------------------------------------
		//  Schema changes
		// -------------------------------------------

		if (version_compare($current, '0.2', '<'))
		{
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_subfolders'));
		}

		if (version_compare($current, '0.3', '<'))
		{
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'upload_file'));
		}

		if (version_compare($current, '0.4', '<'))
		{
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'move_folder'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'create_folder'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'delete_folder'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'move_file'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'delete_file'));
		}

		if (version_compare($current, '0.5', '<'))
		{
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'view_file'));
		}

		if (version_compare($current, '0.6', '<'))
		{
			// {filedir_x}/filename => {filedir_x}filename
			ee()->db->query('UPDATE exp_assets SET file_path = REPLACE(file_path, "}/", "}")');
		}

		if (version_compare($current, '0.7', '<'))
		{
			ee()->load->dbforge();

			// delete unused exp_assets columns
			ee()->dbforge->drop_column('assets', 'asset_kind');
			ee()->dbforge->drop_column('assets', 'file_dir');
			ee()->dbforge->drop_column('assets', 'file_name');
			ee()->dbforge->drop_column('assets', 'file_size');
			ee()->dbforge->drop_column('assets', 'sha1_hash');
			ee()->dbforge->drop_column('assets', 'img_width');
			ee()->dbforge->drop_column('assets', 'img_height');
			ee()->dbforge->drop_column('assets', 'date_added');
			ee()->dbforge->drop_column('assets', 'edit_date');

			// rename 'asset_date' to 'date', and move it after title
			ee()->db->query('ALTER TABLE exp_assets
			                      CHANGE COLUMN `asset_date` `date` INT(10) UNSIGNED NULL DEFAULT NULL  AFTER `title`');
		}

		if (version_compare($current, '0.8', '<'))
		{
			// build_file_manager => build_sheet
			ee()->db->where('method', 'build_file_manager')
				->update('actions', array('method' => 'build_sheet'));
		}

		if (version_compare($current, '1.0.1', '<'))
		{
			// tell EE about the fieldtype's global settings
			ee()->db->where('name', 'assets')
				->update('fieldtypes', array('has_global_settings' => 'y'));
		}

		if (version_compare($current, '1.1.5', '<'))
		{
			ee()->load->dbforge();

			// do we need to add the var_id column to exp_assets_entries?
			//  - the 1.1 update might have added this but then failed on another step, so the version wouldn't be updated
			$query = ee()->db->query('SHOW COLUMNS FROM `'.ee()->db->dbprefix.'assets_entries` LIKE "var_id"');
			if (! $query->num_rows())
			{
				ee()->db->query('ALTER TABLE exp_assets_entries ADD var_id INT(6) UNSIGNED AFTER row_id, ADD INDEX (var_id)');
			}
			else
			{
				// do we need to add its index?
				$query = ee()->db->query('SHOW INDEX FROM exp_assets_entries WHERE Key_name = "var_id"');
				if (! $query->num_rows())
				{
					ee()->db->query('ALTER TABLE exp_assets_entries ADD INDEX (var_id)');
				}
			}

			// do we need to add the unq_file_path index to exp_assets?
			//  - the 1.1 update used to attempt to add this, but it would fail if there was a duplicate file_path
			$query = ee()->db->query('SHOW INDEX FROM exp_assets WHERE Key_name = "unq_file_path"');
			if (! $query->num_rows())
			{
				// are there any duplicate file_path's?
				$query = ee()->db->query('
					SELECT a.asset_id, a.file_path FROM exp_assets a
					INNER JOIN (
						SELECT file_path FROM exp_assets
						GROUP BY file_path HAVING count(asset_id) > 1
					) dup ON a.file_path = dup.file_path');

				if ($query->num_rows())
				{
					$duplicates = array();
					foreach ($query->result() as $asset)
					{
						$duplicates[$asset->file_path][] = $asset->asset_id;
					}

					foreach ($duplicates as $file_path => $asset_ids)
					{
						$first_asset_id = array_shift($asset_ids);

						if (count($asset_ids))
						{
							// point any entries that were using the duplicate IDs over to the first one
							ee()->db->where_in('asset_id', $asset_ids)
								->update('assets_entries', array('asset_id' => $first_asset_id));

							// delete the duplicates in exp_assets
							ee()->db->where_in('asset_id', $asset_ids)
								->delete('assets');
						}
					}
				}

				// now that there are no more unique file_path's, add the unique index,
				// and drop the old file_path index, since that would be redundant
				ee()->db->query('ALTER TABLE exp_assets ADD UNIQUE unq_file_path (file_path), DROP INDEX file_path');
			}
		}

		if (version_compare($current, '2.0b1', '<'))
		{
			ee()->load->dbforge();

			// Set file_path to NOT NULL
			ee()->db->query('ALTER TABLE exp_assets MODIFY COLUMN file_path VARCHAR(255) NOT NULL');

			// on a clean 1.2.1 install, this index might not exist
			$query = ee()->db->query('SHOW INDEX FROM exp_assets WHERE Key_name = "unq_file_path"');
			if ($query->num_rows())
			{
				// Drop the unq_file_path index
				ee()->db->query('ALTER TABLE exp_assets DROP INDEX unq_file_path');
			}

			// Add all the fields to make exp_assets a functional index table
			$fields = array(
				'source_type'		=> array('type' => 'varchar', 'constraint' => 2, 'null' => FALSE, 'default' => 'ee'),
				'source_id'			=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
				'filedir_id'		=> array('type' => 'int', 'constraint' => 4, 'unsigned' => TRUE, 'null' => TRUE),
				'folder_id'			=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
				'date_modified'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
				'kind'				=> array('type' => 'varchar', 'constraint' => 5),
				'width'				=> array('type' => 'int', 'constraint' => 2),
				'height'			=> array('type' => 'int', 'constraint' => 2),
				'size'				=> array('type' => 'int', 'constraint' => 3),
				'search_keywords'	=> array('type' => 'text')
			);

			ee()->dbforge->add_column('assets', $fields);
			ee()->db->query('ALTER TABLE exp_assets CHANGE `file_path` `file_name` VARCHAR (255) NOT NULL');

			// table for storing folder structure
			$fields = array(
				'folder_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'source_type'	=> array('type' => 'varchar', 'constraint' => 2, 'null' => FALSE, 'default' => 'ee'),
				'folder_name'	=> array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE),
				'full_path'		=> array('type' => 'varchar', 'constraint' => 255),
				'parent_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE, 'null' => TRUE),
				'source_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
				'filedir_id'	=> array('type' => 'int', 'constraint' => 4, 'unsigned' => TRUE),
			);

			ee()->dbforge->add_field($fields);
			ee()->dbforge->add_key('folder_id', true);
			ee()->dbforge->create_table('assets_folders');


			// source information
			$fields = array(
				'source_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE),
				'source_type'	=> array('type' => 'varchar', 'constraint' => 2, 'null' => FALSE, 'default' => 's3'),
				'name'			=> array('type' => 'varchar', 'constraint' => 255),
				'settings'		=> array('type' => 'text', 'null' => FALSE)
			);

			ee()->dbforge->add_field($fields);
			ee()->dbforge->add_key('source_id', TRUE);
			ee()->dbforge->create_table('assets_sources');

			// Add new actions
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'rename_folder'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'view_thumbnail'));

			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_session_id'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'start_index'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'perform_index'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'finish_index'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_s3_buckets'));

			// some more table structure changes
			ee()->db->query("ALTER TABLE exp_assets RENAME TO exp_assets_files");
			ee()->db->query("ALTER TABLE exp_assets_files CHANGE `asset_id` `file_id` INT(10) NOT NULL AUTO_INCREMENT");
			ee()->db->query("ALTER TABLE exp_assets_entries RENAME TO exp_assets_selections");
			ee()->db->query("ALTER TABLE exp_assets_selections CHANGE `asset_order` `sort_order` INT(4) UNSIGNED");
			ee()->db->query("ALTER TABLE exp_assets_selections CHANGE `asset_id` `file_id` INT(10)");

			// migrate the existing data
			$this->_migrate_data('<2 -> 2.0');

			// Add the unique indexes
			ee()->db->query('ALTER TABLE exp_assets_files ADD UNIQUE unq_folder_id__file_name (folder_id, file_name)');
			ee()->db->query('ALTER TABLE exp_assets_folders ADD UNIQUE unq_source_type__source_id__parent_id__folder_name (`source_type`, `source_id`, `parent_id`, `folder_name`)');
			ee()->db->query('ALTER TABLE exp_assets_folders ADD UNIQUE unq_source_type__source_id__full_path (`source_type`, `source_id`,  `full_path`)');
			ee()->db->query('ALTER TABLE exp_assets_sources ADD UNIQUE unq_source_type__source_id (`source_type`, `source_id`)');

			// table for temporary data during indexing
			$fields = array(
				'session_id'	=> array('type' => 'char', 'constraint' => 36),
				'source_type'	=> array('type' => 'varchar', 'constraint' => 2),
				'source_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
				'offset'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
				'uri'			=> array('type' => 'varchar', 'constraint' => 255),
				'filesize'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE),
				'type'			=> array('type' => 'enum', 'constraint' => "'file','folder'"),
				'record_id'		=> array('type' => 'int', 'constraint' => 10, 'unsigned' => TRUE)
			);
			ee()->dbforge->add_field($fields);
			ee()->dbforge->create_table('assets_index_data');
			ee()->db->query('ALTER TABLE `exp_assets_index_data` ADD UNIQUE unq__session_id__source_type__source_id__offset (`session_id`, `source_type`, `source_id`, `offset`)');
		}
		elseif (version_compare($current, '2.0b2', '<'))
		{
			// add the new actions
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_session_id'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'start_index'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'perform_index'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'finish_index'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_s3_buckets'));

			// files
			ee()->db->query("ALTER TABLE exp_assets_files CHANGE COLUMN `connector` `source_type` VARCHAR(2) NOT NULL DEFAULT 'ee'");
			ee()->db->query("ALTER TABLE exp_assets_files ADD COLUMN `source_id` INT(10) UNSIGNED NULL AFTER `source_type`");
			ee()->db->query("ALTER TABLE exp_assets_files ADD COLUMN `filedir_id` INT(4) UNSIGNED NULL AFTER `source_id`");
			ee()->db->query("ALTER TABLE exp_assets_files CHANGE COLUMN `folder_id` `folder_id` INT(10) UNSIGNED NULL");

			// folders
			ee()->db->query("ALTER TABLE exp_assets_folders CHANGE COLUMN `connector` `source_type` VARCHAR(2) NOT NULL DEFAULT 'ee'");
			ee()->db->query("ALTER TABLE exp_assets_folders CHANGE COLUMN `pref_id` `source_id` INT(10) UNSIGNED NULL");
			ee()->db->query("ALTER TABLE exp_assets_folders ADD COLUMN `filedir_id` INT(4) UNSIGNED NULL AFTER `source_id`");
			ee()->db->query('ALTER TABLE exp_assets_folders DROP INDEX unq_connector__parent_id__folder_name');
			ee()->db->query('ALTER TABLE exp_assets_folders DROP INDEX unq_connector__pref_id__full_path');
			ee()->db->query('ALTER TABLE exp_assets_folders ADD UNIQUE unq_source_type__source_id__filedir_id__parent_id__folder_name (`source_type`, `source_id`, `filedir_id`, `parent_id`, `folder_name`)');
			ee()->db->query('ALTER TABLE exp_assets_folders ADD UNIQUE unq_source_type__source_id__filedir_id__full_path (`source_type`, `source_id`, `filedir_id`, `full_path`)');

			// index_data
			ee()->db->query("ALTER TABLE exp_assets_sync_data RENAME TO exp_assets_index_data");
			ee()->db->query("ALTER TABLE exp_assets_index_data CHANGE COLUMN `sync_session` `session_id` CHAR(36) NULL");
			ee()->db->query("ALTER TABLE exp_assets_index_data ADD COLUMN `source_type` VARCHAR(2) AFTER `session_id`");
			ee()->db->query("ALTER TABLE exp_assets_index_data CHANGE COLUMN `folder_id` `source_id` INT(10) UNSIGNED NULL");
			ee()->db->query('ALTER TABLE exp_assets_index_data DROP INDEX `sync_index`');
			ee()->db->query('ALTER TABLE exp_assets_index_data ADD UNIQUE unq__session_id__source_type__source_id__offset (`session_id`, `source_type`, `source_id`, `offset`)');

			// sources
			ee()->db->query("ALTER TABLE exp_assets_folder_prefs RENAME TO exp_assets_sources");
			ee()->db->query("ALTER TABLE exp_assets_sources CHANGE COLUMN `pref_id` `source_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
			ee()->db->query("ALTER TABLE exp_assets_sources CHANGE COLUMN `connector` `source_type` VARCHAR(2) NOT NULL DEFAULT 's3'");
			ee()->db->query("ALTER TABLE exp_assets_sources ADD COLUMN `name` VARCHAR(255) DEFAULT '' AFTER `source_type`");
			ee()->db->query("ALTER TABLE exp_assets_sources CHANGE COLUMN `data` `settings` TEXT NOT NULL DEFAULT ''");

			$this->_migrate_data('2.0b1 -> 2.0b2');
		}

		if (version_compare($current, '2.0b4', '<')){
			ee()->load->dbforge();
			$fields = array(
				'is_draft'  	=> array('type' => 'TINYINT', 'constraint' => '1', 'unsigned' => TRUE, 'default' => 0)
			);

			ee()->dbforge->add_column('assets_selections', $fields);

			ee()->db->query("ALTER TABLE exp_assets_files MODIFY COLUMN `folder_id` INT(10) NOT NULL AFTER `file_id`");
			ee()->db->query("ALTER TABLE exp_assets_files MODIFY COLUMN `source_type` VARCHAR(2) NOT NULL DEFAULT 'ee' AFTER `folder_id`");
			ee()->db->query("ALTER TABLE exp_assets_files MODIFY COLUMN `source_id` INT(10) UNSIGNED NULL AFTER `source_type`");
			ee()->db->query("ALTER TABLE exp_assets_files MODIFY COLUMN `filedir_id` INT(4) UNSIGNED NULL AFTER `source_id`");

			ee()->db->query('UPDATE exp_modules SET module_version = "2.0" WHERE module_name = "Assets"');
		}

		if (version_compare($current, '2.1', '<'))
		{
			ee()->load->dbforge();

			// Changes to file date
			ee()->db->query("UPDATE exp_assets_files SET `date` = `date_modified` WHERE `date` IS NULL OR `date` = ''");

			// Removing the NOT NULL condition, because 2.1.4 undoes this anway, and setting this to NOT NULL can cause problems
			// If a file is missing from the server and doesn't have date set.
			ee()->db->query("ALTER TABLE exp_assets_files MODIFY COLUMN `date` INT(10) UNSIGNED NULL");

			// Adding Rackspace cloud and Google cloud actions
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_rs_containers'));
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_gc_containers'));

			// Adding the rackspace connection table
			$fields = array(
				'connection_key' => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE, 'required' => TRUE),
				'token'	         => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE, 'required' => TRUE),
				'storage_url'    => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE, 'required' => TRUE),
				'cdn_url'        => array('type' => 'varchar', 'constraint' => 255, 'null' => FALSE, 'required' => TRUE)
			);

			ee()->dbforge->add_field($fields);
			ee()->dbforge->add_key('connection_key', true);
			ee()->dbforge->create_table('assets_rackspace_access');
		}

		if (version_compare($current, '2.1.2', '<'))
		{
			// Clean up possible incorrect indexes
			$query = ee()->db->query('SHOW INDEX FROM exp_assets_folders WHERE Key_name = "unq_source_type__source_id__full_path"');
			if ($query->num_rows())
			{
				// Drop the unq_file_path index
				ee()->db->query('ALTER TABLE exp_assets_folders DROP INDEX unq_source_type__source_id__full_path');
			}

			$query = ee()->db->query('SHOW INDEX FROM exp_assets_folders WHERE Key_name = "unq_source_type__source_id__parent_id__folder_name"');
			if ($query->num_rows())
			{
				// Drop the unq_file_path index
				ee()->db->query('ALTER TABLE exp_assets_folders DROP INDEX unq_source_type__source_id__parent_id__folder_name');
			}

			// Add new indexes, if needed
			$query = ee()->db->query('SHOW INDEX FROM exp_assets_folders WHERE Key_name = "unq_source_type__source_id__filedir_id__parent_id__folder_name"');
			if (!$query->num_rows())
			{
				ee()->db->query('ALTER TABLE exp_assets_folders ADD UNIQUE unq_source_type__source_id__filedir_id__parent_id__folder_name (`source_type`, `source_id`, `filedir_id`, `parent_id`, `folder_name`)');
			}

			$query = ee()->db->query('SHOW INDEX FROM exp_assets_folders WHERE Key_name = "unq_source_type__source_id__filedir_id__full_path"');
			if (!$query->num_rows())
			{
				ee()->db->query('ALTER TABLE exp_assets_folders ADD UNIQUE unq_source_type__source_id__filedir_id__full_path (`source_type`, `source_id`, `filedir_id`, `full_path`)');
			}


		}

		if (version_compare($current, '2.1.4', '<'))
		{
			ee()->db->query('ALTER TABLE exp_assets_files MODIFY COLUMN `date` INT(10) NULL');
		}

		if (version_compare($current, '2.2', '<'))
		{
			if (!ee()->db->field_exists('element_id', 'assets_selections'))
			{
				ee()->db->query('ALTER TABLE exp_assets_selections ADD COLUMN `element_id` VARCHAR(255) NULL AFTER `var_id`');
			}
			if (!ee()->db->field_exists('content_type', 'assets_selections'))
			{
				ee()->db->query('ALTER TABLE exp_assets_selections ADD COLUMN `content_type` VARCHAR(255) NULL AFTER `element_id`');
			}

			$query = ee()->db->query('SHOW INDEX FROM exp_assets_sources WHERE Key_name = "unq_source_type__source_id"');
			if ($query->num_rows())
			{
				// Drop the unq_file_path index
				ee()->db->query('ALTER TABLE exp_assets_sources DROP INDEX unq_source_type__source_id');
			}

		}

		if (version_compare($current, '2.2.2', '<'))
		{
			// Paranoia will destroy ya
			if (!ee()->db->field_exists('content_type', 'assets_selections') && !version_compare($current, '2.2', '<'))
			{
				ee()->db->query('ALTER TABLE exp_assets_selections ADD COLUMN `content_type` VARCHAR(255) NULL AFTER `element_id`');
			}
			ee()->db->query("UPDATE exp_assets_selections SET content_type = 'matrix' WHERE row_id > 0 AND (content_type = '' OR content_type IS NULL)");
		}

		if (version_compare($current, '2.2.5', '<'))
		{
			ee()->db->insert('actions', array('class' => 'Assets_mcp', 'method' => 'get_rs_regions'));

			$query = "CREATE TABLE IF NOT EXISTS `exp_assets_rackspace_access` (
						  `connection_key` varchar(255) NOT NULL,
						  `token` varchar(255) NOT NULL,
						  `storage_url` varchar(255) NOT NULL,
						  `cdn_url` varchar(255) NOT NULL,
						  PRIMARY KEY (`connection_key`)
						) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			ee()->db->query($query);

			ee()->db->query("DELETE FROM exp_assets_rackspace_access");
			$query = ee()->db->query("SELECT source_id, settings FROM exp_assets_sources WHERE source_type = 'rs'");

			if ($query->num_rows())
			{
				foreach ($query->result() as $row)
				{
					$settings = json_decode($row->settings);
					$settings->region = "";
					$settings = Assets_helper::get_json($settings);
					ee()->db->query("UPDATE exp_assets_sources SET settings = '" . $settings . "' WHERE source_id = " . $row->source_id);
				}
			}
		}

		if (version_compare($current, '2.3', '<'))
		{
			$files = glob(PATH_THIRD.'assets/libraries/*');
			foreach ($files as &$file)
			{
				$file = explode("/", $file);
				$file = array_pop($file);
			}
			$files = array_flip($files);
			// For case sensitive filesystems - delete the lowercased file.
			if (isset($files['Assets_lib.php']) && isset($files['assets_lib.php']))
			{
				unlink(PATH_THIRD.'assets/libraries/assets_lib.php');
			}
		}

		if (version_compare($current, '2.5', '<'))
		{
			ee()->db->query("UPDATE exp_assets_selections SET content_type = 'matrix' WHERE content_type IS NULL AND row_id IS NOT NULL");
		}

		// -------------------------------------------
		//  Update version number in exp_fieldtypes and exp_extensions
		// -------------------------------------------

		ee()->db->where('name', 'assets')
			->update('fieldtypes', array('version' => $this->eeharbor->getConfig('version')));

		ee()->db->where('class', 'Assets_ext')
			->update('extensions', array('version' => $this->eeharbor->getConfig('version')));

		return TRUE;
	}

	/**
	 * Uninstall
	 */
	function uninstall()
	{
		ee()->load->dbforge();

		// routine EE table cleanup

		ee()->db->select('module_id');
		$module_id = ee()->db->get_where('modules', array('module_name' => 'Assets'))->row('module_id');

		ee()->db->where('module_id', $module_id);
		ee()->db->delete('module_member_groups');

		ee()->db->where('module_name', 'Assets');
		ee()->db->delete('modules');

		ee()->db->where('class', 'Assets');
		ee()->db->delete('actions');

		ee()->db->where('class', 'Assets_mcp');
		ee()->db->delete('actions');

		// drop Assets tables
		ee()->dbforge->drop_table('assets_files');
		ee()->dbforge->drop_table('assets_selections');
		ee()->dbforge->drop_table('assets_sources');
		ee()->dbforge->drop_table('assets_folders');
		ee()->dbforge->drop_table('assets_index_data');
		ee()->dbforge->drop_table('assets_rackspace_access');

		$cache_path = ee()->config->item('cache_path');
		if (empty($cache_path))
		{
			$cache_path = APPPATH.'cache/';
		}

		$this->_delete_recursively($cache_path . 'assets');
		return TRUE;
	}

	/**
	 * Delete folder recursively
	 * @param $folder
	 */
	private function _delete_recursively($folder)
	{
		foreach(glob($folder . '/*') as $file)
		{
			if(is_dir($file))
			{
				$this->_delete_recursively($file);
			}
			else
			{
				@unlink($file);
			}
		}
		@rmdir($folder);
	}

	/**
	 * Migrate data according to a scenario
	 * @param $scenario
	 */
	private function _migrate_data($scenario)
	{
		$db = ee()->db;
		ee()->load->library('assets_lib');

		clearstatcache();

		switch ($scenario)
		{
			case '<2 -> 2.0':
				require_once(PATH_THIRD . 'assets/sources/ee/source.ee.php');
				$filedirs = array();
				$folder_list = array();

				// load upload preferences and store them in table for Assets
				$rows = $db->get('upload_prefs')->result();
				foreach ($rows as $filedir)
				{
					$filedirs[$filedir->id] = Assets_ee_source::apply_filedir_overrides($filedir);
				}

				// load physical folder structure
				foreach ($filedirs as $id => $filedir)
				{
					$filedir->server_path = Assets_ee_source::resolve_server_path($filedir->server_path);

					$folder_list[$id][] = $filedir->server_path;
					$this->_load_folder_structure($filedir->server_path, $folder_list[$id]);
				}

				// store the folder structure in database
				$subfolders = array();
				foreach ($folder_list as $filedir_id => $folders)
				{
					$filedir = $filedirs[$filedir_id];
					foreach ($folders as $folder)
					{
						$subpath = substr($folder, strlen($filedir->server_path));
						if (empty($subpath))
						{
							$folder_name = $filedir->name;
							$parent_id = NULL;
						}
						else
						{
							$path_parts = explode('/', $subpath);
							$folder_name = array_pop($path_parts);
							$parent_key = $filedir_id . ':' . rtrim(join('/', $path_parts), '/');
							$parent_id = isset($subfolders[$parent_key]) ? $subfolders[$parent_key] : 0;
						}

						// in case false was returned earlier
						$subpath = $subpath ? rtrim($subpath, '/') . '/' : '';

						$folder_entry = array(
							'source_type' => 'ee',
							'filedir_id' => $filedir_id,
							'folder_name' => $folder_name,
							'full_path' => $subpath
						);

						if ( ! is_null($parent_id))
						{
							$folder_entry['parent_id'] = $parent_id;
						}

						ee()->db->insert('assets_folders', $folder_entry);
						$subfolders[$filedir_id . ':' . rtrim($subpath, '/')] = ee()->db->insert_id();
					}
				}

				// bring up the list of existing assets and update the entries
				$rows = $db->get('assets_files')->result();
				$pattern = '/\{filedir_(?P<filedir_id>[0-9]+)\}(?P<path>.*)/';
				foreach ($rows as $asset)
				{
					$asset->connector = 'ee';
					if (preg_match($pattern, $asset->file_name, $matches))
					{
						if (isset($filedirs[$matches['filedir_id']]))
						{
							$filedir = $filedirs[$matches['filedir_id']];

							$full_path = str_replace('{filedir_' . $filedir->id . '}', $filedir->server_path, $asset->file_name);
							$subpath = substr($full_path, strlen($filedir->server_path));
							$path_parts = explode('/', $subpath);
							$file = array_pop($path_parts);
							$subpath = join('/', $path_parts);

							$folder_key = $matches['filedir_id'] . ':' . $subpath;
							if (isset($subfolders[$folder_key]))
							{
								$folder_id = $subfolders[$folder_key];
							}
							else
							{
								$folder_id = 0;
							}

							$kind = Assets_helper::get_kind($full_path);
							$data = array(
								'source_type' => 'ee',
								'filedir_id' => $filedir->id,
								'folder_id' => $folder_id,
								'file_name' => $file,
								'kind' => $kind,
							);

							if (file_exists($full_path))
							{
								$data['size'] = filesize($full_path);
								$data['date_modified'] = filemtime($full_path);
								if ($kind == 'image')
								{
									list ($width, $height) = getimagesize($full_path);
									$data['width'] = $width;
									$data['height'] = $height;
								}
							}

							ee()->db->update('assets_files', $data, array('file_id' => $asset->file_id));
							ee()->assets_lib->update_file_search_keywords($asset->file_id);
						}
					}
				}

				// celebrate
				break;
			case '2.0b1 -> 2.0b2':

				// get S3 credentials if any
				$query = ee()->db->select('settings')
					->where('name', 'assets')
					->get('fieldtypes');

				$settings = unserialize(base64_decode($query->row('settings')));
				$settings = array_merge(array('license_key' => '', 's3_access_key_id' => '', 's3_secret_access_key' => ''), $settings);

				//if we have s3 settings, let's convert the "folder_prefs" way to "sources" way
				if (!empty($settings['s3_access_key_id']) && !empty($settings['s3_secret_access_key']))
				{
					$old_sources = ee()->db->get('assets_sources')->result();
					foreach ($old_sources as $source)
					{
						$previous_settings = json_decode($source->settings);
						$new_settings = (object) array(
							'access_key_id' => $settings['s3_access_key_id'],
							'secret_access_key' => $settings['s3_secret_access_key'],
							'bucket' => $previous_settings->name,
							'url_prefix' => $previous_settings->url_prefix,
							'location' => $previous_settings->location
						);
						$data = array(
							'name' => $previous_settings->name,
							'settings' => Assets_helper::get_json($new_settings)
						);

						ee()->db->update('assets_sources', $data, array('source_id' => $source->source_id));
					}
				}

				// modify folder data and also keep a list of who's who
				$folders = ee()->db->get('assets_folders')->result();
				$folder_sources = array();
				foreach ($folders as $row)
				{
					if ($row->source_type == 'ee')
					{
						$row->filedir_id = $row->source_id;
						$row->source_id = NULL;
						ee()->db->update('assets_folders', $row, array('folder_id' => $row->folder_id));
						$folder_sources[$row->folder_id] = $row->filedir_id;
					}
					else
					{
						$folder_sources[$row->folder_id] = $row->source_id;
					}
				}

				// add some data for file entries and we're done!
				$files = ee()->db->get('assets_files')->result();
				foreach ($files as $row)
				{
					if ($row->source_type == 'ee' && isset($folder_sources[$row->folder_id]))
					{
						$row->source_id = NULL;
						$row->filedir_id = $folder_sources[$row->folder_id];
						ee()->db->update('assets_files', $row, array('file_id' => $row->file_id));
					}
					else if (isset($folder_sources[$row->folder_id]))
					{
						$row->source_id = $folder_sources[$row->folder_id];
						$row->filedir_id = NULL;
						ee()->db->update('assets_files', $row, array('file_id' => $row->file_id));
					}
				}

				// party!
				break;
		}
	}

	/**
	 * Load the folder structure for data migration
	 *
	 * @param $path
	 * @param $folder_structure
	 */
	private function _load_folder_structure($path, &$folder_structure)
	{
		// starting with underscore or dot gets ignored
		$list = glob($path . '[!_.]*', GLOB_MARK);

		if (is_array($list) && count($list) > 0)
		{
			foreach ($list as $item)
			{
				// parse folders and add files
				$item = Assets_helper::normalize_path($item);
				if (substr($item, -1) == '/')
				{
					// add with dropped slash and parse
					$folder_structure[] = substr($item, 0, -1);
					$this->_load_folder_structure($item, $folder_structure);
				}
			}
		}
	}

	/**
	 * Return true if updating from the $current version requires a DB backup
	 * @param $current
	 * @return bool
	 */
	public function database_backup_required($current)
	{
		if (version_compare($current, '2.0', '<'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
