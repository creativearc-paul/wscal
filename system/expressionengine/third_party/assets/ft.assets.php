<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

include_once('eeharbor.php');

/**
 * Assets Fieldtype
 *
 * @package   Assets
 * @author    EEHarbor <help@eeharbor.com>
 * @copyright Copyright (c) 2016 EEHarbor
 */
class Assets_ft extends EE_Fieldtype
{
	//  EE gets this informations statically... before calling the constructor...
	//  So setting it with the foundation in the constructor wont work -.-
	var $info = array(
		'name'      => 'Assets',
        'version'   => '3.1.0'
	);

	var $has_array_data = TRUE;

	var $row_id; // Set by Matrix
	var $var_id; // Set by Low Variables
	var $is_draft = 0; // Set by Better Workflow
	var $element_id; // Set by Content Elements
	var $element_name; // Set by Content Elements
	var $is_bloq = false;

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// Add Package Path
		ee()->load->add_package_path(PATH_THIRD.'assets/');
		$this->eeharbor = new \assets\EEHarbor;

		$this->info = array(
			'name'    => $this->eeharbor->getConfig('name'),
			'version' => $this->eeharbor->getConfig('version')
		);

		// -------------------------------------------
		//  Prepare Cache
		// -------------------------------------------

		if (! isset(ee()->session->cache['assets']))
		{
			ee()->session->cache['assets'] = array();
		}

		$this->cache =& ee()->session->cache['assets'];

		// -------------------------------------------
		//  Get lib
		// -------------------------------------------
		ee()->load->library('assets_lib');
	}

	// --------------------------------------------------------------------

	/**
	 * Display Global Settings
	 */
	function display_global_settings()
	{
		if (ee()->addons_model->module_installed('assets'))
		{
			ee()->functions->redirect($this->eeharbor->moduleURL('settings'));
		}
		else
		{
			ee()->lang->loadfile('assets');
			$this->eeharbor->flashData('message_failure', lang('no_module'));
			ee()->functions->redirect($this->eeharbor->cpURL('addons'));
		}
	}

	// --------------------------------------------------------------------

	private function _prep_settings(&$settings)
	{
		$settings = array_merge(array(
			'filedirs'       => 'all',
			'multi'          => 'y',
			'view'           => 'thumbs',
			'thumb_size'     => 'small',
			'show_filenames' => 'n',
			'show_cols'      => array('name')
		), $settings);
	}

	/**
	 * Field Settings
	 */
	private function _field_settings($settings, $is_cell = FALSE)
	{
		// prep the settings
		$this->_prep_settings($settings);

		// -------------------------------------------
		//  Include Resources
		// -------------------------------------------

		if (! isset($this->cache['included_resources']))
		{
			Assets_helper::include_css('settings.css');
			Assets_helper::include_js('settings.js');

			// load the language file
			ee()->lang->loadfile('assets');

			$this->cache['included_resources'] = TRUE;
		}

		// get all the file upload directories
		ee()->db->select('id, name')->from('upload_prefs')
			->where('site_id', ee()->config->item('site_id'))
			->order_by('name');

		if($this->eeharbor->getEEVersion(true) >= '3')
		{
			ee()->db->where_not_in('name', array("Avatars", "Default Avatars", "Member Photos", "Signature Attachments", "PM Attachments"));
		}

		$filedirs = ee()->db->get()->result();

		$filedir_array = array();
		foreach ($filedirs as $filedir)
		{
			$filedir_array['ee:' . $filedir->id] = $filedir->name;
		}

		$sources = ee()->db->get('assets_sources')->result();
		$source_array = array();
		foreach ($sources as $source)
		{
			$source_array[$source->source_type . ':' . $source->source_id] = $source->name;
		}

		asort($source_array);

		$filedir_array = array_merge($filedir_array, $source_array);

		if (!empty($settings['filedirs']) && is_array($settings['filedirs']))
		{
			foreach($settings['filedirs'] as &$setting)
			{
				if (is_numeric($setting))
				{
					$setting = 'ee:'.$setting;
				}
			}
		}

		// Settings for EE2 and EE3 are pretty different, so we are just returning different arrays of data
		if($this->eeharbor->ver_gt(2) && !$is_cell)
		{
			$info = array(
				array(
					'title' => lang('file_upload_directories', 'assets_filedirs'),
					'desc' => lang('file_upload_directories_info'),
					'fields' => array(
						'filedirs' => array(
							'type' => 'html',
							'content' => ee()->load->view('field/settings-filedirs', array('data' => $settings['filedirs'], 'filedirs' => $filedir_array), TRUE),
						)
					)
				),
				array(
					'title' => lang('view_options', 'assets_view'),
					'desc' => '',
					'fields' => array(
						'view' => array(
							'type' => 'html',
							'content' => ee()->load->view('field/settings-view', array('settings' => $settings), TRUE),
						)
					)
				),
				array(
					'title' => lang('allow_multiple_selections', 'assets_multi'),
					'desc' => '',
					'fields' => array(
						'multi' => array(
							'type' => 'html',
							'content' => form_dropdown('assets[multi]', array('y'=>lang('yes'), 'n'=>lang('no')), $settings['multi']),
						)
					)
				),
			);
		}
		else
		{
			$info = array(
				// File Upload Directories
				array(
					lang('file_upload_directories', 'assets_filedirs') . (! $is_cell ? '<br/>'.lang('file_upload_directories_info') : ''),
					ee()->load->view('field/settings-filedirs', array('data' => $settings['filedirs'], 'filedirs' => $filedir_array), TRUE)
				),

				// View
				array(
					lang('view_options', 'assets_view'),
					ee()->load->view('field/settings-view', array('settings' => $settings), TRUE)
				),

				// Allow multiple selections?
				array(
					lang('allow_multiple_selections', 'assets_multi'),
					form_dropdown('assets[multi]', array('y'=>lang('yes'), 'n'=>lang('no')), $settings['multi'])
				),
			);
		}

		return $info;
	}

	/**
	 * Display Field Settings
	 */
	function display_settings($data)
	{
		$settings = $this->_field_settings($data);

		// different things for different versions of ee
		if($this->eeharbor->ver_gt(2))
		{
			return array('Assets' => array(
				'label' => 'field_options',
				'group' => 'assets',
				'settings' => $settings
			));
		}
		else
		{
			foreach ($settings as $row)
			{
				if (isset($row['data']))
				{
					ee()->table->add_row($row);
				}
				else
				{
					ee()->table->add_row($row[0], $row[1]);
				}
			}

			return;
		}
	}

	/**
	 * Display Element Settings
	 */
	function display_element_settings($data)
	{
		// Add Package Path, because sometimes EE forgets it.
		ee()->load->add_package_path(PATH_THIRD.'assets/');

		return $this->_field_settings($data);
	}

	/**
	 * Display Grid Settings
	 */
	function grid_display_settings($data)
	{
		// // Add Package Path, because sometimes EE forgets it.
		ee()->load->add_package_path(PATH_THIRD.'assets/');


		// Grid settings for EE2
		if($this->eeharbor->is_ee2())
		{
			$settings = $this->_field_settings($data);

			// Just throw the HTML together.
			foreach ($settings as &$row)
			{
				$row = EE_Fieldtype::grid_settings_row($row[0], $row[1]);
			}

			return $settings;
		}
		else
		{
			$settings = $this->display_settings($data);

			$grid_settings = array();

			foreach ($settings as $value) {
				$grid_settings[$value['label']] = $value['settings'];
			}

			return $grid_settings;
		}
	}


	/**
	 * Display Cell Settings
	 */
	function display_cell_settings($data)
	{
		$rows = $this->_field_settings($data, TRUE);

		$r = '<table class="matrix-col-settings" cellspacing="0" cellpadding="0" border="0">';

		$total_cell_settings = count($rows);

		foreach ($rows as $key => $row)
		{
			$tr_class = '';
			if ($key == 0) $tr_class .= ' matrix-first';
			if ($key == $total_cell_settings-1) $tr_class .= ' matrix-last';

			$r .= "<tr class=\"{$tr_class}\">";

			foreach ($row as $j => $cell)
			{
				if (! is_array($cell))
				{
					$cell = array('data' => $cell);
				}

				if ($j == 0)
				{
					$tag = 'th';
					$attr = 'class="matrix-first"';
				}
				else
				{
					$tag = 'td';
					$attr = 'class="matrix-last"';
				}

				if (isset($cell['style']))
				{
					$attr .= " style=\"{$cell['style']}\"";
				}

				$r .= "<{$tag} {$attr}>".(!empty($cell['data']) ? $cell['data'] : '')."</{$tag}>";
			}

			$r .= '</tr>';
		}

		$r .= '</table>';

		return $r;
	}

	/**
	 * Display Variable Settings
	 */
	function var_display_settings($data)
	{
		// if (! $this->var_id)
		// {
		// 	return array(
		// 		array('', 'Assets requires Low Variables 1.3.7 or later.')
		// 	);
		// }

		return $this->display_settings($data);
	}

	// --------------------------------------------------------------------

	/**
	 * Save Field Settings
	 */
	function save_settings($data)
	{
		if (empty($data['assets']))
		{
			$settings = ee()->input->post('assets');
		}
		else
		{
			$settings = $data['assets'];
		}

		// cross the T's
		$settings['field_fmt'] = 'none';
		$settings['field_show_fmt'] = 'n';
		$settings['field_type'] = 'assets';

		return $settings;
	}

	/**
	 * Save Field Settings
	 */
	function save_element_settings($settings)
	{
		$settings = $settings['assets'];

		return $settings;
	}

	/**
	 * Save Cell Settings
	 */
	function save_cell_settings($settings)
	{
		$settings = $settings['assets'];

		return $settings;
	}

	/**
	 * Save Variable Settings
	 */
	function var_save_settings()
	{
		return ee()->input->post('assets');
	}

	// --------------------------------------------------------------------

	/**
	 * Migrate Field Data
	 */
	private function _migrate_field_data($field_data, $entry_data)
	{
		// Spit the newline-separated data into an array of file paths
		$paths = array_filter(preg_split('/[\r\n]/', $field_data));

		foreach ($paths as $sort_order => $path)
		{
			unset($folder_id, $file_id, $file);

			// is this a valid {filedir_X}filename.ext path?
			if (preg_match('/^\{filedir_(\d+)\}(.*\/)?([^\/]+)$/', $path, $match))
			{
				$filedir_id = $match[1];
				$folder_path = $match[2];
				$filename = $match[3];

				// Do we already have a record of this folder?
				$query = ee()->db->get_where('assets_folders', array(
					'filedir_id' => $filedir_id,
					'full_path'  => $folder_path
				));

				if ($query->num_rows())
				{
					$folder_id = $query->row('folder_id');
					$file_id = ee()->assets_lib->get_file_id_by_folder_id_and_name($folder_id, $filename);
				}

				// If we don't have a record of the file yet, create one.
				if (empty($file_id))
				{
					// Do we at least have a record of the upload directory?
					$query = ee()->db->get_where('assets_folders', array(
						'source_type' => 'ee',
						'filedir_id'  => $filedir_id
					));

					if (!$query->num_rows())
					{
						// hey, we tried our best. it's not our fault. it's not our fault. it's not our fault.
						continue;
					}

					// Make sure the folder records exist
					$parent_id = $query->row('folder_id');
					$folder_parts = array_filter(explode('/', $folder_path));
					$full_path = '';

					// walk through the path parts
					while (! is_null($path_part = array_shift($folder_parts)))
					{
						$full_path .= $path_part . '/';

						// Do we know about this folder?
						$query = ee()->db->get_where('assets_folders', array(
							'source_type' => 'ee',
							'parent_id'   => $parent_id,
							'folder_name' => $path_part
						));

						if ($query->num_rows())
						{
							$parent_id = $query->row('folder_id');
						}
						else
						{
							// Nope. Create one.
							ee()->db->insert('assets_folders', array(
								'source_type' => 'ee',
								'parent_id'   => $parent_id,
								'folder_name' => $path_part,
								'full_path'   => $full_path,
								'source_id'   => NULL,
								'filedir_id'  => $filedir_id,
							));

							$parent_id = ee()->db->insert_id();
						}
					}

					// Now that we have a record of all the folders leading up to the file,
					// register it.
					$file_id = ee()->assets_lib->register_file($parent_id, $filename);
				}
				else
				{
					ee()->assets_lib->update_file_search_keywords($file_id);
				}

				// save the association in exp_assets_selections
				ee()->db->insert('assets_selections', array_merge($entry_data, array(
					'file_id'    => $file_id,
					'sort_order' => $sort_order
				)));
			}
		}
	}

	/**
	 * Modify exp_channel_data Column Settings
	 */
	function settings_modify_column($data)
	{
		// is this a new Assets field?
		if ($data['ee_action'] == 'add')
		{
			$field_id = $data['field_id'];
			$field_name = 'field_id_'.$field_id;

			// Let this run in EE2 and 3, but not EE4.
			// EE4 does channel_data differently
			if($this->eeharbor->ver_lt(4))
			{
				// is this an existing field?
				if (ee()->db->field_exists($field_name, 'channel_data'))
				{
					$entries = ee()->db->select("entry_id, {$field_name}")
						->where("{$field_name} LIKE '{filedir_%'")
						->where("{$field_name} != ", '')
						->get('channel_data');

					foreach ($entries->result() as $entry)
					{
						$this->_migrate_field_data($entry->$field_name, array(
							'entry_id' => $entry->entry_id,
							'field_id' => $field_id
						));
					}
				}
			}
		}
		else if ($data['ee_action'] == 'delete')
		{
			// delete any asset associations created by this field
			ee()->db->where('field_id', $data['field_id'])
				->delete('assets_selections');
		}

		// just return the default column settings
		return parent::settings_modify_column($data);
	}

	/**
	 * Modify exp_matrix_data Column Settings
	 */
	function settings_modify_matrix_column($data)
	{
		// is this a new Assets column?
		if ($data['matrix_action'] == 'add')
		{
			$field_id = ee()->input->post('field_id');
			$col_id = $data['col_id'];
			$col_name = 'col_id_'.$col_id;

			// is this an existing field?
			if ($field_id && ee()->db->field_exists($col_name, 'matrix_data'))
			{
				$rows = ee()->db->select("entry_id, row_id, {$col_name}")
					->where("{$col_name} LIKE '{filedir_%'")
					->where("{$col_name} != ", '')
					->get('matrix_data');

				foreach ($rows->result() as $row)
				{
					$this->_migrate_field_data($row->$col_name, array(
						'entry_id'     => $row->entry_id,
						'field_id'     => $field_id,
						'col_id'       => $col_id,
						'row_id'       => $row->row_id,
						'content_type' => 'matrix'
					));
				}
			}
		}
		else if ($data['matrix_action'] == 'delete')
		{
			// delete any asset associations created by this column
			ee()->db->where('col_id', $data['col_id'])
				->delete('assets_selections');
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Build Field
	 */
	private function _build_field($data, $context)
	{
		// include the resources
		Assets_helper::include_sheet_resources();

		// prep the settings
		$this->_prep_settings($this->settings);
		// -------------------------------------------
		//  Field HTML
		// -------------------------------------------

		if ($is_cell = isset($this->cell_name))
		{
			$vars['field_name'] = $this->cell_name;
			$vars['field_id'] = str_replace(array('[',']'), array('_',''), $this->cell_name);
		}
		else if ($this->var_id)
		{
			$vars['field_name'] = $this->field_name;
			$vars['field_id'] = str_replace(array('[',']'), array('_',''), $this->field_name);
		}
		else if ($this->element_id)
		{
			$vars['field_name'] = $this->field_name;
			$vars['field_id'] = $this->_extract_element_id($this->field_name);
		}
		else if ($context == 'grid')
		{
			$cell_name = 'col_id_'.$this->settings['col_id'];
			$vars['field_id']  = $vars['field_name'] = $cell_name;
		}
		else
		{
			$vars['field_name'] = $vars['field_id'] = $this->field_name;
		}

		// -------------------------------------------
		//  Get the selected files
		// -------------------------------------------

		$entry_id = $this->_get_entry_id();

		$vars['files'] = array();

		// if there was a validation error, EE's already passed us the post array
		if (is_array($data))
		{
			foreach ($data as $file_id)
			{
				if (!empty($file_id))
				{
					$file = ee()->assets_lib->get_file_by_id($file_id, TRUE);

					if ($file !== FALSE)
					{
						$vars['files'][] = $file;
					}
				}
			}
		}

		// should there be existing data?
		else if (
			($context == 'channel' && $entry_id)
			||
			($context == 'matrix' && isset($this->row_id))
			||
			($context == 'grid' && isset($this->settings['grid_row_id']) && isset($this->settings['col_id']))
			||
			($context == 'low')
			||
			($context == 'content_elements')
		)
		{
			if ($context == 'grid')
			{
				// Set up these properties so we can select data the same way we do for Matrix
				$this->row_id = $this->settings['grid_row_id'];
				$this->col_id = $this->settings['col_id'];

				// Also stop lying about our field id.
				$this->field_id = $this->settings['grid_field_id'];
			}

			$sql = "SELECT DISTINCT a.source_type, a.folder_id, a.file_name, a.file_id, af.source_id, af.filedir_id, ae.sort_order
					FROM exp_assets_files AS a
					INNER JOIN exp_assets_selections AS ae ON ae.file_id = a.file_id
					INNER JOIN exp_assets_folders AS af ON af.folder_id = a.folder_id
					WHERE";

			switch ($context)
			{
				case 'low':
					$sql .= " ae.var_id = {$this->var_id}";
					break;

				case 'content_elements':
					$element_id = $this->_extract_element_id($this->field_name);
					$sql .= ' ae.element_id = "'.$element_id.'"';

					$is_draft = isset(ee()->session->cache['ep_better_workflow']['is_draft']) && ee()->session->cache['ep_better_workflow']['is_draft'];
					$sql .= ' AND is_draft = ' . ($is_draft ? '1' : '0') . ' ';

					break;

				case 'matrix':
				case 'grid':
					$sql .= " ae.col_id = '{$this->col_id}'
								  AND ae.row_id = '{$this->row_id}'
								  AND";
					if (!empty($this->var_id))
					{
						$sql .= " ae.var_id = " . $this->var_id;
					}
					else
					{
						$entry_id = $this->eeharbor->xss_clean($entry_id);
						if (!$entry_id && !empty($this->settings['entry_id']))
						{
							$entry_id = $this->settings['entry_id'];
						}

						$sql .= " ae.entry_id ". ($entry_id ? "= '{$entry_id}'" : 'IS NULL')
							. " AND ae.field_id ". ($this->field_id ? "= '{$this->field_id}'" : 'IS NULL');
					}
					break;
				case 'channel':
					$entry_id = $this->eeharbor->xss_clean($entry_id);

					$sql .= " ae.entry_id ". ($entry_id ? "= '{$entry_id}'" : 'IS NULL')
						. " AND ae.field_id ". ($this->field_id ? "= '{$this->field_id}'" : 'IS NULL');

			}

			$sql .= ' ORDER BY ae.sort_order';

			if ($this->settings['multi'] == 'n')
			{
				$sql .= ' LIMIT 1';
			}

			// -------------------------------------------
			//  'assets_field_selections_query' hook
			//   - Modify the row data before it gets saved to exp_assets_selections
			//
			if (ee()->extensions->active_hook('assets_field_selections_query'))
			{
				$query = ee()->extensions->call('assets_field_selections_query', $this, $sql);
			}
			else
			{
				$query = ee()->db->query($sql);
			}
			//
			// -------------------------------------------


			foreach ($query->result() as $row)
			{
				try
				{
					if ($file = ee()->assets_lib->get_file_by_id($row->file_id, TRUE))
					{
						$vars['files'][] = $file;
					}
				}
				catch (Exception $exception)
				{
					// these files are gone.
				}
			}
		}

		$vars['multi'] = ($this->settings['multi'] == 'y');

		$vars['helper'] = ee()->assets_lib;

		if ($this->settings['view'] == 'thumbs')
		{
			// load the filemanager library and file helper for generating thumbs
			ee()->load->library('filemanager');
			ee()->load->helper('file');

			$vars['file_view'] = 'thumbview/thumbview';
			$vars['thumb_size'] = $this->settings['thumb_size'];
			$vars['show_filenames'] = ($this->settings['show_filenames'] == 'y');
		}
		else
		{
			$vars['file_view'] = 'listview/listview';
			$vars['cols']   = $this->settings['show_cols'];
		}

		if ($context == 'content_elements')
		{
			$vars['ce_options'] = Assets_helper::get_json($this->settings);
		}
		else
		{
			$vars['ce_options'] = NULL;
		}

		$r = ee()->load->view('field/field', $vars, TRUE);

		// Add a hidden input in case no files are selected
		$r .= '<input type="hidden" name="'.($is_cell ? $this->cell_name : $this->field_name).'[]" value="" />';

		// Include any thumb CSS queued up by the field
		Assets_helper::insert_queued_css();

		// -------------------------------------------
		//  Pass field settings to JS
		// -------------------------------------------

		if (!$is_cell || !isset($this->cache['initialized_col_settings'][$this->col_id]))
		{
			if ($is_cell)
			{
				$namespace = 'col_id_'.$this->col_id;
			}
			else if ($context == 'content_elements')
			{
				$namespace = 'element_id_'.$this->element_id;
			}
			else
			{
				$namespace = 'field_id_'.$this->field_id;
			}

			$settings_json = Assets_helper::get_json(array(
				'filedirs'       => $this->settings['filedirs'],
				'multi'          => $vars['multi'],
				'view'           => $this->settings['view'],
				'thumb_size'     => $this->settings['thumb_size'],
				'show_filenames' => $this->settings['show_filenames'],
				'show_cols'      => $this->settings['show_cols'],
				'namespace'      => $namespace
			));

			if ($is_cell)
			{
				Assets_helper::insert_js('Assets.Field.matrixConfs.col_id_'.$this->col_id.' = '.$settings_json.';');
				$this->cache['initialized_col_settings'][$this->col_id] = TRUE;
			}
			if (!empty($this->element_id))
			{
				if ($this->field_name != '__element_name__[__index__][data]')
				{
					$field_id = $this->_extract_element_id($this->field_name);
					Assets_helper::insert_js("new Assets.Field('{$field_id}', '{$this->field_name}', {$settings_json});");
				}
			}
			else if ($context == 'grid')
			{
				Assets_helper::insert_js('Assets.Field.gridConfs.col_id_'.$this->settings['col_id'].' = '.$settings_json.';');
			}
			else
			{
				$field_id = preg_replace('/[^\w\-]+/', '_', $vars['field_id']);
				Assets_helper::insert_js("new Assets.Field('{$field_id}', '{$this->field_name}', {$settings_json});");
			}
		}

		return $r;
	}

	/**
	 * Display Field
	 */
	function display_field($data)
	{
		return $this->_build_field($data, 'channel');
	}

	/**
	 * Display Cell
	 */
	function display_cell($data)
	{
		// include the resources
		Assets_helper::include_sheet_resources();

		if (! isset($this->cache['included_matrix_resources']))
		{
			Assets_helper::include_js('matrix.js');

			$this->cache['included_matrix_resources'] = TRUE;
		}

		return array(
			'data' => $this->_build_field($data, 'matrix'),
			'class' => 'assets'
		);
	}

	/**
	 * Display Grid Cell
	 */
	function grid_display_field($data)
	{
		// include the resources
		Assets_helper::include_sheet_resources();

		if (! isset($this->cache['included_grid_resources']))
		{
			Assets_helper::include_js('grid.js');

			$this->cache['included_grid_resources'] = TRUE;
		}

		return $this->_build_field($data, 'grid');
	}

	/**
	 * Display Variable Field
	 */
	function var_display_field($data)
	{
		if (! $this->var_id)
		{
			return "";
		}

		// Load this each time, because EE might have discarded it.
		ee()->load->add_package_path(PATH_THIRD.'assets');

		return $this->_build_field($data, 'low');
	}

	/**
	 * Display Content Elements Field
	 *
	 * @param string $data
	 * @return string
	 */
	function display_element($data)
	{
		// have we included the CE script?
		if (! isset($this->cache['included_content_resources']))
		{
			Assets_helper::include_js('content_elements.js');
			$this->cache['included_content_resources'] = TRUE;
		}

		// Load this each time, because EE might have discarded it.
		ee()->load->add_package_path(PATH_THIRD.'assets');

		return $this->_build_field($data, 'content_elements');
	}

	// --------------------------------------------------------------------

	/**
	 * Validate
	 */
	function validate($data)
	{
		$require_upload = FALSE;

		// is this a required field?
		if ($this->settings['field_required'] == 'y' && ! (is_array($data) && array_filter($data)))
		{
			if (empty($_FILES))
			{
				return lang('required');
			}
			else
			{
				$require_upload = TRUE;
			}
		}

		if (!empty($_FILES))
		{
			$field_name = ee()->db->get_where('channel_fields', array('field_id' => $this->field_id))->row()->field_name;
			$filedir_id = ee()->input->post($field_name .'_filedir');

			if (!empty($_FILES[$field_name]['name']) && !empty($filedir_id))
			{
				$source = ee()->assets_lib->instantiate_source_type((object) array('source_type' => 'ee', 'filedir_id' => $filedir_id));
				$filedir = $source->settings();

				if ($filedir->max_size)
				{
					if (!is_array($_FILES[$field_name]['tmp_name']))
					{
						$sizes = array($_FILES[$field_name]['size']);
					}
					else
					{
						$sizes = $_FILES[$field_name]['size'];
					}

					foreach ($sizes as $size)
					{
						if ($size > $filedir->max_size)
						{
							ee()->lang->loadfile('assets');

							return ee()->functions->var_swap(lang('file_too_large'), array(
								'max_size' => Assets_helper::format_filesize($filedir->max_size)
							));

						}
					}
				}
			}
			elseif ($require_upload)
			{
				return lang('required');
			}
		}

		return TRUE;
	}

	/**
	 * Validate Cell
	 */
	function validate_cell($data)
	{
		// is this a required cell?
		if ($this->settings['col_required'] == 'y' && ! (is_array($data) && array_filter($data)))
		{
			return lang('col_required');
		}

		return TRUE;
	}

	/**
	 * Get Filenames
	 */
	private function _get_filenames($file_ids)
	{
		$file_names = array();

		if ($file_ids)
		{
			$query = ee()->db->select('file_name')
				->where_in('file_id', $file_ids)
				->get('assets_files');

			foreach ($query->result() as $asset)
			{
				$file_names[] = $asset->file_name;
			}
		}

		return implode("\n", $file_names);
	}

	/**
	 * Save a BWF draft.
	 *
	 * @param $data
	 * @return mixed
	 */
	public function draft_save($data)
	{
		$this->field_id = $this->settings['field_id'];
		$this->is_draft = 1;
		$this->post_save($data);
		return $data;
	}

	/**
	 * Save a BWF draft for Content Elements element.
	 *
	 * @param $data
	 * @return mixed
	 */
	public function save_element_draft($data)
	{
		$this->is_draft = 1;
		$this->post_save_element($data);
		return $data;
	}

	/**
	 * Discard a BWF draft.
	 */
	public function draft_discard()
	{
		$where = array(
			'entry_id' => $this->_get_entry_id(),
			'field_id' => $this->settings['field_id'],
			'is_draft' => 1
		);

		ee()->db->where($where)->delete('assets_selections');
	}

	/**
	 * Discard a BWF draft for a content element.
	 */
	public function discard_element_draft($data)
	{
		$where = array(
			'element_id' => $this->element_id,
			'is_draft' => 1
		);

		ee()->db->where($where)->delete('assets_selections');
	}

	/**
	 * Publish a BWF draft.
	 */
	public function draft_publish()
	{
		$where = array(
			'entry_id' => $this->_get_entry_id(),
			'field_id' => $this->settings['field_id'],
			'is_draft' => 0
		);
		ee()->db->where($where)->delete('assets_selections');

		$where['is_draft'] = 1;
		$update = array('is_draft' => 0);
		ee()->db->where($where)->update('assets_selections', $update);

		return;
	}

	/**
	 * Publish a BWF draft for a content element.
	 */
	public function publish_element_draft($data)
	{

		$where = array(
			'element_id' => $this->element_id,
			'is_draft' => 0
		);
		ee()->db->where($where)->delete('assets_selections');

		$where['is_draft'] = 1;
		$update = array('is_draft' => 0);
		ee()->db->where($where)->update('assets_selections', $update);

		return;
	}


	function save($file_ids)
	{
		// If we have a lone file_id passed, make it into an array.
		if (is_numeric($file_ids))
		{
			$file_ids = array($file_ids);
		}

		// By now it should be an array or it does not belong here.
		if (!is_array($file_ids))
		{
			$file_ids = array();
		}

		$file_ids = array_filter($file_ids);

		$new_uploads = FALSE;
		$field_name = ee()->db->get_where('channel_fields', array('field_id' => $this->field_id))->row()->field_name;

		if (!empty($_FILES[$field_name]['name']))
		{
			$file_field = $_FILES[$field_name]['name'];

			// In case of multiple file upload, the FILE field will be an array which requires more checking
			$new_uploads = !empty($file_field) && (is_array($file_field) && count($file_field) == 1 ? !empty($file_field[0]) : TRUE);
		}

		// ignore if it doesn't look like submitted Assets data
		if ($new_uploads)
		{
			$filedir_id = ee()->input->post($field_name .'_filedir');
			$source_id = ee()->input->post($field_name .'_source');

			// We need a target filedir as well.
			if (!empty($filedir_id) || !empty($source_id))
			{
				$file_data = array();

				// If multiple files are submitted we can always rely on PHP to mess up the _FILES array.
				if (is_array($_FILES[$field_name]['name']))
				{
					foreach ($_FILES[$field_name]['name'] as $index => $name)
					{
						$file_data[] = array('name' => $name, 'tmp_name' => $_FILES[$field_name]['tmp_name'][$index]);
					}
				}
				else
				{
					$file_data[] = array('name' => $_FILES[$field_name]['name'], 'tmp_name' => $_FILES[$field_name]['tmp_name']);
				}

				foreach ($file_data as $data)
				{
					$file_ids[] = $this->_simple_html_upload($data, $filedir_id, $source_id);
				}

			}
		}

		// save the post data for later
		$this->cache['field_data'][$this->field_id] = $file_ids;

		// return the filenames
		return $this->_get_filenames($file_ids);
	}

	/**
	 * Save Element
	 */
	function save_element($data)
	{
		if (!$this->element_id)
		{
			return '';
		}

		// ignore if it doesn't look like Assets data
		if (! is_array($data)) return '';

		$file_ids = array_filter($data);

		$this->cache['content_elements'][$this->element_id] = $file_ids;

		// return the filenames
		return $this->_get_filenames($file_ids);
	}

	/**
	 * Save Grid
	 */
	function grid_save($data)
	{

		// ignore if it doesn't look like Assets data
		if (empty($this->settings['col_id']) OR empty($this->settings['grid_row_name'])) {
			return '';
		}

		if (!is_array($data))
		{
			$field_name = ee()->db->get_where('channel_fields', array('field_id' => $this->settings['grid_field_id']))->row()->field_name;
			$row_name = $this->settings['grid_row_name'];
			$col_name = $this->field_name;

			$filedir_id = isset($_POST[$field_name][$row_name][$col_name.'_filedir']) ? $_POST[$field_name][$row_name][$col_name.'_filedir'] : '';
			$source_id = isset($_POST[$field_name][$row_name][$col_name.'_source']) ? $_POST[$field_name][$row_name][$col_name.'_source'] : '';


			if (!empty($_FILES[$col_name]['name']) && (!empty($filedir_id) || !empty($source_id)))
			{
				$file_array = array(
					'name' => $_FILES[$col_name]['name'],
					'tmp_name' => $_FILES[$col_name]['tmp_name']);

				$file_ids = array($this->_simple_html_upload($file_array, $filedir_id, $source_id));
			}
			else
			{
				return '';
			}
		}
		else
		{
			$file_ids = array_filter($data);
		}

		$this->cache['field_data']['grid'][$this->settings['grid_row_name']][$this->settings['col_id']] = $file_ids;

		// return the filenames
		return $this->_get_filenames($file_ids);
	}

	/**
	 * Save Cell
	 */
	function save_cell($file_ids)
	{

		// ignore if it doesn't look like submitted Assets data
		if (! is_array($file_ids))
		{
			$field_name = ee()->db->get_where('channel_fields', array('field_id' => $this->field_id))->row()->field_name;
			$row_name = $this->settings['row_name'];
			$col_name = $this->settings['col_name'];
			$filedir_id = isset($_POST[$field_name][$row_name][$col_name.'_filedir']) ? $_POST[$field_name][$row_name][$col_name.'_filedir'] : '';
			$source_id = isset($_POST[$field_name][$row_name][$col_name.'_source']) ? $_POST[$field_name][$row_name][$col_name.'_source'] : '';


			if (!empty($_FILES[$field_name]['name'][$row_name][$col_name]) && (!empty($filedir_id) || !empty($source_id)))
			{
				$file_array = array(
					'name' => $_FILES[$field_name]['name'][$row_name][$col_name],
					'tmp_name' => $_FILES[$field_name]['tmp_name'][$row_name][$col_name]);

				$file_ids = array($this->_simple_html_upload($file_array, $filedir_id, $source_id));
			}
			else
			{
				return;
			}
		}

		// save the post data for later
		$id = ($this->var_id ? $this->var_id : $this->field_id);
		$this->cache['field_data'][$id][$this->settings['col_id']][$this->settings['row_name']] = $file_ids;

		// return the filenames
		return $this->_get_filenames($file_ids);
	}

	/**
	 * Save Variable Field
	 */
	function var_save($data)
	{
		if (! $this->var_id) return;

		// ignore if it doesn't look like Assets data
		if (! is_array($data)) return;

		$file_ids = array_filter($data);

		$where = array(
			'var_id' => $this->var_id
		);

		// save the changes
		$this->_save_field($file_ids, $where);

		// return the filenames
		return $this->_get_filenames($file_ids);
	}

	// --------------------------------------------------------------------

	/**
	 * Post Save
	 */
	function post_save($data)
	{
		// ignore if we didn't cache the asset IDs
		if (! isset($this->cache['field_data'][$this->field_id])) return;

		// get the asset IDs from the cache
		$file_ids = $this->cache['field_data'][$this->field_id];
		if (!is_array($file_ids))
		{
			$file_ids = array();
		}

		$where = array(
			'entry_id' => $this->_get_entry_id(),
			'field_id' => $this->field_id,
			'is_draft' => $this->is_draft
		);

		// save the changes
		$this->_save_field($file_ids, $where);
	}

	/**
	 * Post Save
	 */

	function post_save_element ($data)
	{

		if (!$this->element_id)
		{
			return;
		}

		if (!isset($this->cache['content_elements'][$this->element_id]))
		{
			return;
		}

		$file_ids = $this->cache['content_elements'][$this->element_id];

		$where = array(
			'element_id' => $this->element_id
		);

		if ($this->is_draft OR isset(ee()->session->cache['ep_better_workflow']['is_draft']) && ee()->session->cache['ep_better_workflow']['is_draft'])
		{
			$where['is_draft'] = 1;
		}

		// save the changes
		$this->_save_field($file_ids, $where);
	}


	/**
	 * Post-save Grid data.
	 *
	 * @param $data
	 */
	function grid_post_save($data)
	{
		if (empty($this->cache['field_data']['grid'][$this->settings['grid_row_name']][$this->settings['col_id']]))
		{
			$file_ids = array();
		}
		else
		{
			$file_ids = $this->cache['field_data']['grid'][$this->settings['grid_row_name']][$this->settings['col_id']];
		}

		$where = array(
			'col_id'       => $this->settings['col_id'],
			'row_id'       => $this->settings['grid_row_id'],
			'field_id'     => $this->settings['grid_field_id'],
			'entry_id'     => $this->_get_entry_id(),
			'content_type' => 'grid'
		);

		// save the changes
		$this->_save_field($file_ids, $where);
	}

	/**
	 * Post Save Cell
	 */
	function post_save_cell($data)
	{
		$id = ($this->var_id ? $this->var_id : $this->field_id);

		// ignore if we didn't cache the asset IDs
		if (! isset($this->cache['field_data'][$id][$this->settings['col_id']][$this->settings['row_name']])) return;

		// get the asset IDs from the cache
		$file_ids = $this->cache['field_data'][$id][$this->settings['col_id']][$this->settings['row_name']];

		$where = array(
			'col_id'       => $this->settings['col_id'],
			'row_id'       => $this->settings['row_id'],
			'content_type' => 'matrix'
		);

		if ($this->var_id)
		{
			$where['var_id'] = $this->var_id;
		}
		else
		{
			$where['entry_id'] = $this->_get_entry_id();
			$where['field_id'] = $this->field_id;
		}

		if ($this->is_draft)
		{
			$where['is_draft'] = $this->is_draft;
		}

		// save the changes
		$this->_save_field($file_ids, $where);
	}

	/**
	 * Save Selections
	 */
	private function _save_field($file_ids, $where)
	{
		// -------------------------------------------
		//  'assets_save_row' hook
		//   - Modify the row data before it gets saved to exp_assets_selections
		//
		if (ee()->extensions->active_hook('assets_save_row'))
		{
			$where = ee()->extensions->call('assets_save_row', $this, $where);
		}
		//
		// -------------------------------------------

		// delete previous selections
		ee()->db->where($where)
			->delete('assets_selections');

		if ($file_ids)
		{
			foreach ($file_ids as $sort_order => $file_id)
			{
				if (!empty($file_id))
				{
					$selection_data = array_merge($where, array(
						'file_id'    => $file_id,
						'sort_order' => $sort_order
					));

					ee()->db->insert('assets_selections', $selection_data);
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Delete Entries
	 */
	function delete($entry_ids)
	{
		ee()->db->where_in('entry_id', $entry_ids)
			->delete('assets_selections');
	}

	/**
	 * Delete Grid Rows.
	 *
	 * @param $row_ids
	 */
	function grid_delete($row_ids)
	{
		ee()->db->where_in('row_id', $row_ids)
			->where('field_id', $this->settings['grid_field_id'])
			->where('content_type', 'grid')
			->delete('assets_selections');
	}

	/**
	 * Delete Rows
	 */
	function delete_rows($row_ids)
	{
		ee()->db->where_in('row_id', $row_ids)
			->where('content_type', 'matrix')
			->delete('assets_selections');
	}

	/**
	 * Delete Variable
	 */
	function var_delete($var_id)
	{
		ee()->db->where('var_id', $var_id)
			->delete('assets_selections');
	}

	// --------------------------------------------------------------------

	/**
	 * Pre Process
	 */
	function pre_process($data)
	{

		// -------------------------------------------
		//  Get the exp_assets_selections rows
		// -------------------------------------------

		// Check if this is from Bloqs (Formerly Blocks) and if so, treat it like a grid (but note that it's still a bloq).
		if(isset($this->content_type) && $this->content_type == 'blocks') {
			$this->is_bloq = true;
			$this->content_type = 'grid';
		}

		// Grid specific
		if (isset($this->content_type) && $this->content_type == 'grid')
		{
			$this->field_id = $this->settings['grid_field_id'];
			$this->col_id = $this->settings['col_id'];
			$this->row_id = $this->settings['grid_row_id'];
		}

		// $this->field_id can NOT be relied upon if Channel Form is in use. If multiple Assets are being used in the
		// field group, this will always be set to the last Asset field in the group.
		if (!empty($this->settings['field_id']))
		{
			$this->field_id = $this->settings['field_id'];
		}

		// did the extension get called and do we have the requested entries?
		if(isset($this->cache['assets_selections_rows']) && ! $this->row_id && ! $this->var_id
			&& isset($this->cache['assets_selections_rows'][$this->row['entry_id']][$this->field_id]))
		{
			$rows = $this->cache['assets_selections_rows'][$this->row['entry_id']][$this->field_id];
		}
		else
		{

			$sql = 'SELECT DISTINCT a.*, ae.sort_order FROM exp_assets_files AS a
				INNER JOIN exp_assets_folders AS af ON af.folder_id = a.folder_id
				INNER JOIN exp_assets_selections AS ae ON ae.file_id = a.file_id';

			if ($this->var_id)
			{
				$sql .= ' WHERE ae.var_id = '.$this->var_id;
			}
			else
			{
				$sql .= ' WHERE ae.entry_id = "'.$this->row['entry_id'].'"
						AND ae.field_id = "'.$this->field_id.'"';
			}

			if ($this->row_id)
			{
				$sql .= ' AND ae.col_id = "'.$this->col_id.'" AND ae.row_id = "'.$this->row_id.'"';
			}

			$sql .= ' AND ae.is_draft = ' . (int) $this->is_draft;

			$sql .= ' ORDER BY ae.sort_order';

			// -------------------------------------------
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
			//
			// -------------------------------------------

			$rows = $query->result_array();
		}

		// Since EE doesn't bother creating a new Fieldtype object for different context,
		// we have to reset this, otherwise parsing non-Matrix Assets fields in the same entry
		// are not possible once an Assets field is parsed within a Grid field.
		if (isset($this->content_type) && $this->content_type == 'grid')
		{
			$this->row_id = NULL;
		}

		// -------------------------------------------
		//  Get the files
		// -------------------------------------------

		$files = array();

		foreach ($rows as $row)
		{
			try
			{
				$source = ee()->assets_lib->instantiate_source_type((object) $row);
				{
					if ($file = $source->get_file($row['file_id'], FALSE, $row))
					{
						$files[] = $file;
					}
				}
			}
			catch (Exception $exception)
			{
				continue;
			}
		}

		return $files;
	}

	// --------------------------------------------------------------------

	/**
	 * Apply Params
	 */
	private function _apply_params(&$data, $params)
	{
		// ignore if there are no selected files
		if (! $data || !is_array($data)) return;

		// -------------------------------------------
		//  Orderby and Sort
		// -------------------------------------------

		if (isset($params['orderby']))
		{
			$orderbys = explode('|', $params['orderby']);
			$sorts = isset($params['sort']) ? explode('|', $params['sort']) : array();

			foreach ($orderbys as $i => $orderby)
			{
				foreach ($data as $file)
				{
					$ms_arrays[$orderby][] = strtolower($file->$orderby());
				}

				$ms_params[] = $ms_arrays[$orderby];
				$ms_params[] = (isset($sorts[$i]) && $sorts[$i] == 'desc') ? SORT_DESC : SORT_ASC;
			}

			$ms_params[] =& $data;

			call_user_func_array('array_multisort', $ms_params);
		}

		else if (isset($params['sort']))
		{
			switch ($params['sort'])
			{
				case 'desc':
					$data = array_reverse($data);
					break;

				case 'random':
					shuffle($data);
					break;
			}
		}

		// -------------------------------------------
		//  Search filter params
		// -------------------------------------------

		// Asset_id is an alias for file_id, but file_id takes precedence.
		if (isset($params['asset_id']))
		{
			if (!isset($params['file_id']))
			{
				$params['file_id'] = $params['asset_id'];
			}
			unset($params['asset_id']);
		}

		$prop_params = array('server_path', 'subfolder', 'filename', 'extension', 'date_modified', 'kind', 'width', 'height', 'size', 'asset_id', 'file_id');

		if (!empty($data[0]))
		{
			$meta_params = array_keys($data[0]->row());
			$search_params = array_merge($prop_params, $meta_params);
		}
		else
		{
			$search_params = $prop_params;
		}

		foreach ($search_params as $param)
		{
			if (isset($params[$param]) && ($val = $params[$param]))
			{
				// exact match?
				if ($exact = (strncmp($val, '=', 1) == 0))
				{
					$val = substr($val, 1);
				}

				// negative match?
				if ($not = (strncmp($val, 'not ', 4) == 0))
				{
					$val = substr($val, 4);
				}

				// all required?
				$all_required = (strpos($val, '&&') !== FALSE);

				// get individual terms
				$conj = $all_required ? '&&' : '|';
				$terms = explode($conj, $val);

				foreach ($data as $i => $file)
				{
					$include_file = $all_required;

					foreach ($terms as $term)
					{
						// get the actual value
						$actual_val = in_array($param, $prop_params) ? $file->$param() : $file->row_field($param);

						// comparison match?
						if (preg_match('/^[<>]=?/', $term, $m))
						{
							$term = substr($term, strlen($m[0]));
							eval('$match = ($actual_val && ($actual_val '.$m[0].' $term));');
						}
						else
						{
							// looking for empty?
							if ($empty = ($term == 'IS_EMPTY'))
							{
								$term = '';
							}

							// exact match?
							if ($exact || $empty)
							{
								$match = (strcasecmp($actual_val, $term) == 0);
							}
							else
							{
								$match = (stripos($actual_val, $term) !== FALSE);
							}
						}

						// if all are required, exclude the file on the first non-match
						if ($all_required && ! $match)
						{
							$include_file = false;
							break;
						}

						// if one is required, include the file on the first match
						if (! $all_required && $match)
						{
							$include_file = true;
							break;
						}
					}

					// remove the file from the $data array if it should be excluded
					if ($not == $include_file)
					{
						array_splice($data, $i, 1);
					}
				}
			}
		}

		// -------------------------------------------
		//  Offset and Limit
		// -------------------------------------------

		if (isset($params['offset']) || isset($params['limit']))
		{
			$offset = isset($params['offset']) ? (int) $params['offset'] : 0;
			$limit  = isset($params['limit'])  ? (int) $params['limit']  : count($data);

			$data = array_splice($data, $offset, $limit);
		}
	}

	/**
	 * Filter by Kind
	 */
	private function _filter_by_kind($file)
	{
		return in_array($file->kind(), $this->_kinds);
	}

	// --------------------------------------------------------------------

	/**
	 * Replace Tag
	 */
	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
		// return the full URL if there's no tagdata
		if (! $tagdata) return $this->replace_url($data, $params);

		$var_prefix = (isset($params['var_prefix']) && $params['var_prefix']) ? rtrim($params['var_prefix'], ':') . ':' : '';

		// get the absolute number of files before we run the filters
		$vars[$var_prefix.'absolute_total_files'] = count($data);

		$this->_apply_params($data, $params);

		// Only trigger this for Low Variables
		if (! $data && $this->var_id)
		{
			return ee()->TMPL->no_results();
		}

		// get the filtered number of files
		$vars[$var_prefix.'total_files'] = count($data);

		// parse {total_files} and {absolute_total_files} now, since they'll never change
		$tagdata = ee()->TMPL->parse_variables_row($tagdata, $vars);

		$r = Assets_helper::parse_file_tag($data, $tagdata, $var_prefix);

		// -------------------------------------------
		//  Backspace param
		// -------------------------------------------

		if (isset($params['backspace']))
		{
			$chop = strlen($r) - $params['backspace'];
			$r = substr($r, 0, $chop);
		}

		$this->var_id = null;

		return $r;
	}

	/**
	 * Replace Grid cell
	 */
	function grid_replace_tag($data, $params = array(), $tagdata = FALSE)
	{
		// Bloqs doesn't need any of the grid_replace_tag stuff, so just skip to the replace_tag.
		if($this->is_bloq) return $this->replace_tag($data, $params, $tagdata);

		$field_name = ee()->db->select('field_name')->from('channel_fields')->where(array('field_id' => $this->field_id))->get()->row('field_name');

		$tagparts = ee()->TMPL->tagparts;
		if (! $field_name || (isset($tagparts[0]) && $tagparts[0] == 'low_variables'))
		{
			$field_name = ee()->db->select('variable_name')->from('global_variables')->where('variable_id', $this->field_id)->get()->row('variable_name');
		}

		$col_name = ee()->db->select('col_name')->from('grid_columns')->where(array('field_id' => $this->field_id, 'col_id' => $this->col_id))->get()->row('col_name');

		$var_prefix = (isset($params['var_prefix']) && $params['var_prefix']) ? rtrim($params['var_prefix'], ':') . ':' : '';
		$var_prefix = $var_prefix . $field_name . ':' . $col_name;

		$params['var_prefix'] = $var_prefix;

		return $this->replace_tag($data, $params, $tagdata);

	}

	/**
	 * Render the element.
	 *
	 * @param $data
	 * @param array $params
	 * @param $tagdata
	 * @return bool
	 */
	function replace_element_tag($data, $params = array(), $tagdata = FALSE)
	{
		ee()->load->add_package_path(PATH_THIRD.'assets/');

		if ($data && $this->element_id)
		{
			$is_draft = isset(ee()->session->cache['ep_better_workflow']['is_draft']) && ee()->session->cache['ep_better_workflow']['is_draft'];

			// array_values is applied here to re-index the array, as we need the keys to be in a numerical order.
			$data = array_values(ee()->assets_lib->get_file_by_id(ee()->assets_lib->get_file_ids_by_element_id($this->element_id, $is_draft)));
			$tagdata = ee()->functions->var_swap($tagdata, array('element_name' => $this->element_name));
			if (preg_match_all('/(\{files(\s.*?)?\}(.*?)\{\/files\})/s', $tagdata, $matches))
			{
				foreach ($matches[1] as $index => $matched_markup)
				{
					$params = array();
					if (!empty($matches[2][$index]))
					{
						$parameters = array_filter(preg_split("/\s/", $matches[2][$index]));
						foreach ($parameters as $parameter)
						{
							if (strpos($parameter, '='))
							{
								list ($key, $value) = explode("=", $parameter);
								$params[$key] = trim($value, "'" . '"');
							}
						}
					}
					$replace = $this->replace_tag($data, $params, $matches[3][$index]);
					$tagdata = str_replace($matches[1][$index], $replace, $tagdata);
				}
			}
			return $tagdata;
		}
	}

	/**
	 * Catches {assets_field:manipulation} and {assets_field:tag_func:manipulation} tags
	 *
	 * @param Assets_base_file $file_info
	 * @param array $params
	 * @param mixed $tagdata
	 * @param $modifier
	 * @return bool|string
	 */
	function replace_tag_catchall($file_info, $params = array(), $tagdata = FALSE, $modifier = '')
	{
		if ($modifier && is_array($file_info))
		{
			$this->_apply_params($file_info, $params);

			if (!empty($file_info))
			{
				$modifier_parts = explode(':', $modifier);

				$file = array_shift($file_info);

				if (!is_object($file))
				{
					return;
				}

				if (count($modifier_parts) == 2)
				{
					$tag_func = $modifier_parts[0];
					$manipulation = $modifier_parts[1];

					return $file->$tag_func($manipulation);
				}
				else
				{
					return $this->replace_tag($file_info, $params, $tagdata);
				}
			}
		}

		return '';
	}

	/**
	 * Display Variable Tag
	 */
	function var_replace_tag($data)
	{
		if (! $this->var_id) return;

		$data = $this->pre_process($data);
		return $this->replace_tag($data, ee()->TMPL->tagparams, ee()->TMPL->tagdata);
	}

	// --------------------------------------------------------------------

	/**
	 * Replace Tag
	 */
	function replace_total_files($data, $params)
	{
		$this->_apply_params($data, $params);

		return (string) count($data);
	}

	/**
	 * Replace URL
	 */
	function replace_url($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->url();
	}

	/**
	 * Replace Revved URL
	 */
	function replace_revved_url($data, $params)
	{
		return $data[0]->revved_url();
	}

	/**
	 * Replace Server Path
	 */
	function replace_server_path($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->server_path();
	}

	/**
	 * Replace Subfolder
	 */
	function replace_subfolder($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->subfolder();
	}

	/**
	 * Replace Source Subfolder
	 */
	function replace_source_subfolder($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->source_subfolder();
	}

	/**
	 * Replace Filename
	 */
	function replace_filename($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->filename_sans_extension();
	}

	/**
	 * Replace Extension
	 */
	function replace_extension($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->extension();
	}

	/**
	 * Replace Date Modified
	 */
	function replace_date_modified($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		$format = '';
		if (isset($params['format']))
		{
			$format = $params['format'];
		}
		$date = $data[0]->date_modified($format);

		if ($format)
		{
			$date = ee()->localize->format_date($format, $date);
		}

		return $date;

	}

	/**
	 * Replace Kind
	 */
	function replace_kind($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->kind();
	}

	/**
	 * Replace Width
	 */
	function replace_width($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->width();
	}

	/**
	 * Replace Height
	 */
	function replace_height($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->height();
	}

	/**
	 * Replace Size
	 */
	function replace_size($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		$size = $data[0]->size();

		if (isset($params['unformatted']) && $params['unformatted'] == "yes")
		{
			return $size;
		}

		$size = Assets_helper::format_filesize($size);

		return ($size == '2 GB' ? '> 2 GB' : $size);
	}

	/**
	 * Replace Asset Id
	 */
	function replace_file_id($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->file_id();
	}

	/**
	 * Replace Title
	 */
	function replace_title($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->row_field('title');
	}

	/**
	 * Replace Date
	 */
	function replace_date($data, $params)
	{

		$this->_apply_params($data, $params);
		if (! $data) return;

		$format = '';
		if (isset($params['format']))
		{
			$format = $params['format'];
		}

		$date = $data[0]->row_field('date');

		if ($format)
		{
			$date = date(str_replace('%', '', $format), $date);
		}

		return $date;

	}

	/**
	 * Replace Alt Text
	 */
	function replace_alt_text($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->row_field('alt_text');
	}

	/**
	 * Replace Caption
	 */
	function replace_caption($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->row_field('caption');
	}

	/**
	 * Replace Author
	 */
	function replace_author($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->row_field('author');
	}

	/**
	 * Replace Description
	 */
	function replace_desc($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->row_field('desc');
	}

	/**
	 * Replace Location
	 */
	function replace_location($data, $params)
	{
		$this->_apply_params($data, $params);
		if (! $data) return;

		return $data[0]->row_field('location');
	}

	/**
	 * Extract a CE element id from field name
	 *
	 * @param $field_name
	 *
	 * @return string
	 */
	private function _extract_element_id($field_name)
	{
		if (!preg_match('/\[([a-z0-9]+)\]\[data\]/i', $this->field_name, $matches))
		{
			return '';
		}

		return $matches[1];
	}

	/**
	 * Make Assets Grid / Low Variable compatible.
	 *
	 * @param $name
	 *
	 * @return bool
	 */
	public function accepts_content_type($name)
	{
		return ($name == 'channel' || $name == 'grid' || $name == 'low_variables');
	}

	/**
	 * Perform the simple HTML upload for frontend forms.
	 *
	 * @param $file_info
	 * @param $filedir_id
	 * @param $source_id
	 *
	 * @return mixed
	 */
	private function _simple_html_upload($file_info, $filedir_id = null, $source_id = null)
	{
		$file_name = $file_info['name'];

		if (empty($filedir_id) && empty($source_id))
		{
			return false;
		}

		$params = array(
			'parent_id' => NULL,
		);

		if (empty($source_id))
		{
			$params['filedir_id'] = $filedir_id;
		}
		else
		{
			$params['source_id'] = $source_id;
		}

		$folder_id = ee()->assets_lib->get_folder_id_by_params(
			$params
		);

		if (!$folder_id)
		{
			return FALSE;
		}

		$folder = ee()->assets_lib->get_folder_row_by_id($folder_id);

		try
		{
			if (!empty($filedir_id))
			{
				$source = ee()->assets_lib->instantiate_source_type((object) array('source_type' => 'ee', 'filedir_id' => $filedir_id));
				$filedir = $source->settings();

				if($filedir->max_size && filesize($file_info['tmp_name']) > $filedir->max_size)
				{
					return FALSE;
				}
			}
			else
			{
				$source_row = ee()->assets_lib->get_source_row_by_id($source_id);
				$source = ee()->assets_lib->instantiate_source_type($source_row);
			}

			$file_name = ee()->assets_lib->clean_filename($file_name);
			$file_name = $source->get_name_replacement($folder, $file_name);

			$result = $source->upload_file($folder_id, $file_info['tmp_name'], $file_name);

			if (isset($result['file_id']))
			{
				$file_id = $result['file_id'];
			}
			else
			{
				return FALSE;
			}

			return $file_id;
		}
		catch (Exception $exception)
		{
			return FALSE;
		}
	}

	/**
	 * Pass on search keywords to Low Search or Super Search
	 */
	public function third_party_search_index($data)
	{
		static $cache = array();

		$entry_id = $this->_get_entry_id();
		$field_id = $this->settings['field_id'];
		$key = $entry_id.'.'.$field_id;

		if ( ! isset($cache[$key]))
		{
			$query = ee()->db->select('f.search_keywords, s.sort_order')
				->from('assets_selections s')
				->join('assets_files f', 's.file_id = f.file_id')
				->where('s.entry_id', $entry_id)
				->where('s.field_id', $field_id)
				->order_by('s.sort_order')
				->get();

			$keywords = array();

			foreach ($query->result() AS $row)
			{
				$keywords[] = $row->search_keywords;
			}

			$cache[$key] = implode('|', array_filter($keywords));
		}

		return $cache[$key];
	}

	protected function _display_nested_form($fields, $values, $child = FALSE)
	{
		$out      = '';
		$disabled = ($this->get_setting('field_disabled')) ? 'disabled' : '';

		foreach ($fields as $id => $option)
		{
			$checked = (in_array(form_prep($option), $values)) ? TRUE : FALSE;

			if (is_array($option))
			{
				$out .= '<label>'.form_checkbox($this->field_name.'[]', $id, $checked, $disabled).NBS.$option['name'].'</label>';
				$out .= $this->_display_nested_form($option['children'], $values, TRUE);
			}
			else
			{
				$out .= '<label>'.form_checkbox($this->field_name.'[]', $id, $checked, $disabled).NBS.$option.'</label>';
			}
		}

		return $out;
	}

	/**
	 * Pretty much try everything to get the entry_id. I think these are all the possible ways to get it, so this should not fail.
	 * @return [type] [description]
	 */
	protected function _get_entry_id()
	{
		$entry_id = null;

		if(array_key_exists("entry_id",$this->settings)) $entry_id = $this->settings['entry_id'];

		if(!$entry_id) $entry_id = ee()->input->get('entry_id');
		if(!$entry_id) $entry_id = ee()->input->post('entry_id');
		if(!$entry_id) $entry_id = $this->content_id();
		if(!$entry_id && !empty($this->row_id)) {
			$row_query = ee()->db->get_where('matrix_data', array('row_id'=>$this->row_id), 1);
			if($row_query->result()) {
				$entry_id = $row_query->row()->entry_id;
			}
		}

		return $entry_id;
	}
}
