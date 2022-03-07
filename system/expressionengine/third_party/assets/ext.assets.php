<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

include_once('eeharbor.php');

/**
 * Assets Extension
 *
 * @package   Assets
 * @author    EEHarbor <help@eeharbor.com>
 * @copyright Copyright (c) 2016 EEHarbor
 */
class Assets_ext extends \assets\Eeharbor_ext
{
	var $name;
	var $version;
	var $description;
	var $docs_url;
	var $settings_exist = 'n';

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// -------------------------------------------
		//  Make a local reference to the EE super object
		// -------------------------------------------

		$this->eeharbor = new \assets\EEHarbor;

		ee()->load->add_package_path(PATH_THIRD.'assets/');
		ee()->load->library('assets_lib');

		$this->name = $this->eeharbor->getConfig('name');
		$this->version = $this->eeharbor->getConfig('version');
		$this->description = $this->eeharbor->getConfig('description');
		$this->docs_url = $this->eeharbor->getConfig('docs_url');

		// -------------------------------------------
		//  Prepare Cache
		// -------------------------------------------

		if (! isset(ee()->session->cache['assets']))
		{
			ee()->session->cache['assets'] = array();
		}

		$this->cache =& ee()->session->cache['assets'];

		if (!isset($this->cache['registered_files']))
		{
			$this->cache['registered_files'] = array();
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Activate Extension
	 */
	public function activate_extension()
	{
		$this->register_extension('channel_entries_query_result', null, 10);
		$this->register_extension('file_after_save', null, 9);
		$this->register_extension('files_after_delete', null, 8);
		$this->register_extension('cp_custom_menu', null, 8);
	}

	/**
	 * Update Extension
	 */
	public function update_extension($current = FALSE)
	{
		$updated = false;

		if ( ! $current || $current == $this->version)
			return FALSE;

		// add cp_custom_menu hook
		if (version_compare($current, '3.0.0', "<")) {
			$this->register_extension("cp_custom_menu");
			$updated = true;
		}

		if($updated) $this->update_version();
	}

	/**
	 * Disable Extension
	 */
	public function disable_extension()
	{
		parent::disable_extension();
	}

	/**************************************************\
	 ******************* ALL HOOKS: *******************
	\**************************************************/

	/**
	 * channel_entries_query_result
	 */
	public function channel_entries_query_result($Channel, $query_result)
	{
		// -------------------------------------------
		//  Get the latest version of $query_result
		// -------------------------------------------

		if (ee()->extensions->last_call !== FALSE)
		{
			$query_result = ee()->extensions->last_call;
		}

		if ($query_result)
		{
			// -------------------------------------------
			//  Get all of the Assets fields that belong to entries' sites
			// -------------------------------------------

			$all_assets_fields = array();

			foreach (ee()->TMPL->site_ids as $site_id)
			{
				if (isset($Channel->pfields[$site_id]))
				{
					foreach ($Channel->pfields[$site_id] as $field_id => $field_type)
					{
						if ($field_type == 'assets')
						{
							// Now get the field name
							if (($field_name = array_search($field_id, $Channel->cfields[$site_id])) !== FALSE)
							{
								$all_assets_fields[$field_id] = $field_name;
							}
						}
					}
				}
			}

			if ($all_assets_fields)
			{
				// -------------------------------------------
				//  Figure out which of those fields are being used in this template
				// -------------------------------------------

				$tmpl_fields = array_merge(
					array_keys(ee()->TMPL->var_single),
					array_keys(ee()->TMPL->var_pair)
				);

				$tmpl_assets_fields = array();

				foreach ($tmpl_fields as $field)
				{
					// Get the actual field name, sans tag func name and parameters
					preg_match('/^[\w\d-]*/', $field, $m);
					$field_name = $m[0];

					$field_ids = array_keys($all_assets_fields, $field_name);
					foreach ($field_ids as $field_id)
					{
						$tmpl_assets_fields[] = $field_id;
					}

				}

				if ($tmpl_assets_fields)
				{
					// -------------------------------------------
					//  Get each of the entry IDs
					// -------------------------------------------

					$entry_ids = array();

					foreach ($query_result as $entry)
					{
						if (! empty($entry['entry_id']))
						{
							$entry_ids[] = $entry['entry_id'];
						}
					}

					// -------------------------------------------
					//  Get all of the exp_assets_selections rows that will be needed
					// -------------------------------------------

					// Set it first so that if there are simply no files selected,
					// the fieldtype still knows the extension was called
					$this->cache['assets_selections_rows'] = array();

					// Set draft only to true if EP BWF is targeting exactly this entry in preview
					$draft_status = (int) (
						isset(ee()->session->cache['ep_better_workflow']['is_draft'])
							&& ee()->session->cache['ep_better_workflow']['is_draft']
							&& count($entry_ids) == 1
							&& isset(ee()->session->cache['ep_better_workflow']['preview_entry_data'])
							&& ee()->session->cache['ep_better_workflow']['preview_entry_data']->entry_id == $entry_ids[0]);

					if ($entry_ids)
					{
						$sql = 'SELECT DISTINCT a.file_id, a.*, ae.* FROM exp_assets_files a
								   INNER JOIN exp_assets_selections ae ON ae.file_id = a.file_id
								   WHERE ae.entry_id IN ('.implode(',', $entry_ids).')
									 AND ae.field_id IN ('.implode(',', $tmpl_assets_fields).')
									 AND is_draft = ' . $draft_status . '
								   ORDER BY ae.sort_order';

						//  'assets_data_query' hook
						//   - Modify the row data before it gets saved to exp_assets_selections
						//
						if (ee()->extensions->active_hook('assets_data_query'))
						{
							$query = ee()->extensions->call('assets_data_query', $this, $sql);
						}
						else
						{
							$query = ee()->db->query($sql);
						}

						foreach ($query->result_array() as $row)
						{
							$this->cache['assets_selections_rows'][$row['entry_id']][$row['field_id']][] = $row;
						}
					}
				}
			}
		}

		return $query_result;
	}

	/**
	 * Register the file in Assets tables
	 * @param $file_id
	 * @param $data
	 */
	public function file_after_save($file_id, $data)
	{
		if (empty($data['upload_location_id']) OR isset($this->cache['filemanager_extension_ignore_files'][$data['upload_location_id'].$data['file_name']]))
		{
			return;
		}

		ee()->assets_lib->register_ee_file($data['upload_location_id'], $data['title']);
	}

	/**
	 * Unregister the file from Assets tables
	 * @param $file_rows
	 */
	public function files_after_delete($file_rows)
	{
		foreach ($file_rows as $file_row)
		{
			$row = ee()->db->get_where('assets_files', array('filedir_id' => $file_row->upload_location_id, 'file_name' => $file_row->title))->row();
			if ($row)
			{
				ee()->assets_lib->unregister_file($row->file_id);
			}
		}
	}

}
