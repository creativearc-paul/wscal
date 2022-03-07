<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

include_once('eeharbor.php');

/**
 * Assets Control Panel
 *
 * @package   Assets
 * @author    EEHarbor <help@eeharbor.com>
 * @copyright Copyright (c) 2016 EEHarbor
 */
class Assets_mcp
{
	var $version;

	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->eeharbor = new \assets\EEHarbor;

		//  Prepare Cache
		if (! isset(ee()->session->cache['assets']))
		{
			ee()->session->cache['assets'] = array();
		}

		$this->cache =& ee()->session->cache['assets'];

		// Load our awesome stuff
		ee()->load->library('assets_lib');
		ee()->lang->loadfile('assets');

		$this->version = $this->eeharbor->getConfig('version');

		// this functionality is available only to logged in users
		if (REQ == 'ACTION' && (!ee()->session->userdata('member_id') OR ee()->session->userdata('is_banned')))
		{
			$this->_forbidden();
		}

		//if not on the index, show the nav with $this->showNav
		if($this->eeharbor->getCurrentUrlInfo("method") != "index")
			$this->showNav();
	}

	/**
	 * Set Page Title and Breadcrumb
	 */
	private function _set_page_title($line = 'assets_module_name')
	{
		if (version_compare(APP_VER, '2.6', '<'))
		{
			ee()->cp->set_variable('cp_page_title', ee()->lang->line($line));
		}
		else
		{
			ee()->view->cp_page_title = ee()->lang->line($line);
		}

		if ($line != 'assets_module_name')
		{
			//TODO: This could be a bug
			ee()->cp->set_breadcrumb(BASE.AMP.$this->eeharbor->getBaseURL(), ee()->lang->line('assets_module_name'));
		}
	}

	// -------------------------------------------
	//  File Manager
	// -------------------------------------------

	/**
	 * File Manager page
	 */
	function index()
	{
		$this->_set_page_title();

		// if EE3, the nav is loaded differently, but in ee2 we still need it loaded the same way
		if($this->eeharbor->is_ee2())
			$this->showNav();

		Assets_helper::include_css('assets.css');

		// display the DB backup message?
		if (!empty($this->cache['show_dbbackup']))
		{
			return ee()->load->view('mcp/dbbackup', NULL, TRUE);
		}

		ee()->cp->add_js_script(array('ui' => array('datepicker')));

		Assets_helper::insert_css('#mainContent .rightNav span { position: relative; z-index: 1; }
			#mainContent .heading { position: relative; top: -32px; margin-bottom: -32px; background: none; }
			#mainContent .heading h2 { padding: 0; color: #34424b; text-shadow: none; background: none; font-size: 25px; font-family: HelveticaNeue-Light, HelveticaNeue, sans-serif; border:none }
			#mainContent .pageContents { margin: 0 -19px -25px -25px; padding: 0; background: none; overflow: visible; border:none !important }');

		Assets_helper::include_garnish();
		Assets_helper::include_js('assets.js');

		$js = Assets_helper::get_actions_js()."\n"
				. Assets_helper::get_lang_js('upload_files', 'upload_status', 'showing', 'of', 'file', 'files',
					'selected', 'new_subfolder', 'rename', '_delete', 'view_file', 'edit_file',
					'confirm_delete_folder', 'confirm_delete_file', 'confirm_delete_files',
					'how_to_proceed', 'apply_to_remaining_conflicts', 'perform_selected', 'couldnt_upload')."\n"
		    . 'Assets.siteUrl = "'.Assets_helper::get_site_url()."\";\n"
		    . 'new Assets.FileManager(jQuery(".assets-fm"), {namespace: "' . ee()->config->item('site_id') . '_panel", context: "filemanager"});';

		Assets_helper::insert_js($js);

		// $vars['base'] = $this->eeharbor->getBaseURL();
		$vars['lib'] = ee()->assets_lib;
		$vars['ee_version'] = $this->eeharbor->getEEVersion($major = true);

		ee()->load->library('table');

		return ee()->load->view('mcp/index', $vars, TRUE);
	}

	/**
	 * View thumbnail
	 */
	function view_thumbnail()
	{
		$file_id = ee()->input->get('file_id');
		$size = ee()->input->get('size');

		try
		{
			$thumbnail_location = ee()->assets_lib->get_thumbnail_location($file_id, $size);
		}
		catch (Exception $exception)
		{
			die();
		}

		if ( ! $thumbnail_location)
		{
			die();
		}

		// Store in browser cache for 30 days
		$cache_time = 60 * 60 * 24 * 30;
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_time) . ' GMT');
		header('Cache-Control: max-age=' . $cache_time . ', must-revalidate');

		$file = ee()->assets_lib->get_file_by_id($file_id, TRUE);
		switch (strtolower($file->extension()))
		{
			case 'jpg':
			case 'jpeg':
				header('Content-Type: image/jpeg');
				break;
			case 'png';
				header('Content-Type: image/png');
				break;
			case 'gif':
				header('Content-Type: image/gif');
				break;
		}

		if (function_exists("header_remove"))
		{
			header_remove('Pragma');
		}
		else
		{
			header('Pragma:');
		}

		readfile($thumbnail_location);
		die();
	}

	/**
	 * Create Folder
	 */
	function create_folder()
	{
		ee()->load->library('javascript');

		$parent_folder = ee()->input->post('parent_folder');
		$folder_name = ee()->input->post('folder_name');

		try
		{
			$output = ee()->assets_lib->create_folder($parent_folder, $folder_name);
		}
		catch (Exception $exception)
		{
			$output = array('error' => $exception->getMessage());
		}

		exit(Assets_helper::get_json($output));
	}

	/**
	 * Rename folder
	 */
	function rename_folder()
	{
		ee()->load->library('javascript');

		$folder_id = ee()->input->post('folder_id');
		$new_name = ee()->input->post('new_name');

		try
		{
			$output = ee()->assets_lib->rename_folder($folder_id, $new_name);
		}
		catch (Exception $exception)
		{
			$output = array('error' => $exception->getMessage());
		}

		exit(Assets_helper::get_json($output));
	}

	/**
	 * Move Folder(s)
	 */
	function move_folder()
	{
		ee()->load->library('javascript');

		$old_ids = ee()->input->post('old_id');
		$new_parents = ee()->input->post('parent_id');
		$actions = ee()->input->post('action');

		$batch_mode = is_array($old_ids);

		if (! ($batch_mode ))
		{
			$old_ids = array($old_ids);
			$new_parents = array($new_parents);
			$actions = array($actions);
		}

		try
		{
			$output = ee()->assets_lib->move_folder($old_ids, $new_parents, $actions);
			if (! $batch_mode)
			{
				$output = $output[0];
			}
		}
		catch (Exception $exception)
		{
			$output = array('error' => $exception->getMessage());
		}

		exit(Assets_helper::get_json($output));
	}

	/**
	 * Delete Folder
	 */
	function delete_folder()
	{
		ee()->load->library('javascript');

		$id = ee()->input->post('folder_id');
		try
		{
			$output = ee()->assets_lib->delete_folder($id);
		}
		catch (Exception $exception)
		{
			$output = array('error' => $exception->getMessage());
		}

		exit(Assets_helper::get_json($output));
	}

	/**
	 * Get Files View
	 *
	 */
	function get_files_view_by_folders()
	{
		ee()->load->library('javascript');

		$keywords = array_filter(explode(' ', (string) ee()->input->post('keywords')));
		$search_type = ee()->input->post('search_type');

		$folders  = ee()->input->post('folders');
		$kinds = ee()->input->post('kinds');

		$selected_file_paths = ee()->input->post('selected_files');

		$orderby = ee()->input->post('orderby');
		$sort = ee()->input->post('sort');
		$offset = ee()->input->post('offset', 0);

		$special = ee()->input->post('special');

		$files = array();
		$total = 0;
		$template_variables = array();

		if ($folders)
		{
			$parameters = array(
				'folders' => $folders,
				'keywords' => $keywords,
				'orderby' => $orderby,
				'sort' => $sort,
				'kinds' => $kinds,
				'search_type' => $search_type,
				'offset' => $offset
			);

			try
			{
				if (!empty($special) && $special == 'recent')
				{
					$files = ee()->assets_lib->get_recent_files($parameters);
				}
				else
				{
					$files = ee()->assets_lib->get_files($parameters);
				}
				$total = count($files);
			}
			catch (Exception $exception)
			{
				// just don't display any files
			}
			$this->_mark_selected_files($files, $selected_file_paths);
		}

		$result = array();

		$template_variables['helper'] = ee()->assets_lib;
		$template_variables['files']  = $files;

		// pass the disabled files
		$disabled_files = ee()->input->post('disabled_files');
		$template_variables['disabled_files'] = $disabled_files ? $disabled_files : array();

		$view = ee()->input->post('view');

		if ($view == 'list')
		{
			$show_modified = in_array(ee()->config->item('assets_show_modified_date'), array("y", "yes", 1));

			if ($search_type == 'deep' || count($folders) > 1)
			{
				if ($show_modified)
				{
					$template_variables['cols'] = array('folder', 'date_modified', 'date', 'size');
				}
				else
				{
					$template_variables['cols'] = array('folder', 'date', 'size');
				}
			}
			else
			{
				if ($show_modified)
				{
					$template_variables['cols'] = array('date_modified', 'date', 'size');
				}
				else
				{
					$template_variables['cols'] = array('date', 'size');
				}
			}

			$template_variables['orderby'] = $orderby;
			$template_variables['sort']    = $sort;

			$result['html'] = ee()->load->view('listview/listview', $template_variables, TRUE);
		}
		else
		{
			if ($view == 'thumbs')
			{
				$template_variables['thumb_size'] = 'small';
				$template_variables['show_filenames'] = FALSE;
			}
			else
			{
				$template_variables['thumb_size'] = 'large';
				$template_variables['show_filenames'] = TRUE;
			}

			$result['html'] = ee()->load->view('thumbview/thumbview', $template_variables, TRUE);
		}

		// Return any thumb CSS queued up by the field
		$result['css'] = Assets_helper::get_queued_css();

		// pass back the requestId so the JS knows the response matches the request
		$result['requestId'] = ee()->input->post('requestId');
		$result['total'] = $total;

		exit(Assets_helper::get_json($result));
	}

	/**
	 * Mark selected files
	 *
	 * @access private
	 * @param $file_list
	 * @param $selected_file_paths
	 * @return array
	 */
	private function _mark_selected_files($file_list, $selected_file_paths)
	{
		$selected_files = array();

		if ( ! is_array($selected_file_paths))
		{
			$selected_file_paths = array();
		}
		foreach ($file_list as $file)
		{
			if (in_array($file->file_id(), $selected_file_paths))
			{
				$file->selected = TRUE;
				$selected_files[] = $file;
			}
		}
		return $selected_files;
	}

	/**
	 * Upload File
	 */
	function upload_file()
	{
		ee()->load->library('javascript');

		// get the upload folder
		$folder = ee()->input->get('folder');

		$file_name = ee()->input->post('file_name');
		$action = ee()->input->post('action');
		$action_info = ee()->input->post('additional_info');
		try
		{
			$output = ee()->assets_lib->upload_file($folder, $action, $action_info, $file_name);
		}
		catch (Exception $exception)
		{
			$output = array('error' => ee()->functions->var_swap(lang('error_uploading_file'),
				array('error' => $exception->getMessage())));
		}

		exit(Assets_helper::get_json($output));
	}

	/**
	 * View File
	 */
	function view_file()
	{
		$id = ee()->input->get('file_id');
		try
		{
			$file_url = ee()->assets_lib->get_file_url($id);
		}
		catch (Exception $exception)
		{
			$file_url = Assets_helper::get_site_url();
		}

		header("Location: " . $file_url);
		die();

	}

	/**
	 * Move or Rename File(s)
	 */
	function move_file()
	{
		ee()->load->library('javascript');

		$old_ids = ee()->input->post('old_id');
		$folder_id = ee()->input->post('folder_id');
		$file_name = ee()->input->post('file_name');
		$actions = ee()->input->post('action');

		$batch_mode = is_array($old_ids);

		if (! ($batch_mode ))
		{
			$old_ids = array($old_ids);
			$folder_ids = array($folder_id);
			$file_names = array($file_name);
			$actions = array($actions);
		}

		try
		{
			$output = ee()->assets_lib->move_file($old_ids, $folder_ids, $file_names, $actions);
			if (! $batch_mode)
			{
				$output = $output[0];
			}
		}
		catch (Exception $exception)
		{
			$output = array('error' => $exception->getMessage());
		}

		exit(Assets_helper::get_json($output));
	}

	/**
	 * Delete File
	 */
	function delete_file()
	{
		ee()->load->library('javascript');

		$ids = ee()->input->post('file_id');

		if (! ($batch_mode = is_array($ids)))
		{
			$ids = array($ids);
		}

		try
		{
			$output = ee()->assets_lib->delete_file($ids);
			if (! $batch_mode)
			{
				$output = $output[0];
			}
		}
		catch (Exception $exception)
		{
			$output = array('error' => $exception->getMessage());
		}

		exit(Assets_helper::get_json($output));
	}

	/**
	 * Get File Properties HTML
	 */
	function get_props()
	{
		ee()->load->library('javascript');

		$id = ee()->input->post('file_id');

		try
		{
			$file = ee()->assets_lib->get_file_by_id($id);
		}
		catch (Exception $exception)
		{
			$file = FALSE;
		}

		$output = array();
		if ($file)
		{
			$vars['file'] = $file;
			$vars['timestamp'] = version_compare(APP_VER, '2.6', '<') ? ee()->localize->set_localized_time($file->row_field('date') * 1000) : ee()->localize->format_date("%U", $file->row_field('date'));
			$vars['human_readable_time'] = version_compare(APP_VER, '2.6', '<') ? ee()->localize->set_human_time($file->row_field('date')) : ee()->localize->format_date("%Y-%m-%d %h:%i %A", $file->row_field('date'));

			switch ($file->kind())
			{
				case 'image': $vars['author_lang'] = 'credit'; break;
				case 'video': $vars['author_lang'] = 'producer'; break;
				default: $vars['author_lang'] = 'author';
			}

			$output['html'] = ee()->load->view('properties', $vars, TRUE);
		}
		else
		{
			$output['html'] = lang('invalid_file');
		}

		$output['requestId'] = ee()->input->post('requestId');

		exit(Assets_helper::get_json($output));
	}

	/**
	 * Save Props
	 */
	function save_props()
	{
		$id = $this->eeharbor->xss_clean(ee()->input->post('file_id'));
		$data = $this->eeharbor->xss_clean(ee()->input->post('data'));

		// convert the formatted dates to Unix timestamps
		foreach ($data as $key => $val)
		{
			if (strpos($key, 'date') !== FALSE && $val)
			{
				$data[$key] = ee()->localize->string_to_timestamp($val);
			}
		}

		ee()->assets_lib->save_file_properties($id, $data);

	}

	/**
	 * Get Ordered Files View
	 */
	function get_ordered_files_view()
	{
		ee()->load->library('javascript');

		$files = ee()->input->post('files');
		$orderby = ee()->input->post('orderby');
		$sort = ee()->input->post('sort');

		$files = ($files ? $files : array());

		// convert file paths to objects
		foreach ($files as $i => $id)
		{
			try
			{
				$files[$i] = ee()->assets_lib->get_file_by_id($id);
			}
			catch (Exception $exception)
			{
				// no-op
			}
		}

		Assets_helper::sort_files($files, $orderby, $sort);

		$vars['helper'] = ee()->assets_lib;
		$vars['files'] = $files;
		$vars['orderby'] = $orderby;
		$vars['sort'] = $sort;
		$vars['field_id']   = ee()->input->post('field_id');
		$vars['field_name'] = ee()->input->post('field_name');

		if (($show_cols = ee()->input->post('show_cols')) !== FALSE)
		{
			$vars['cols'] = $show_cols;
		}

		$output['html'] = ee()->load->view('listview/listview', $vars, TRUE);

		$output['requestId'] = ee()->input->post('requestId');

		exit(Assets_helper::get_json($output));
	}

	/**
	 * Build Sheet
	 */
	function build_sheet()
	{
		$vars['lib'] = ee()->assets_lib;
		$vars['mode'] = 'sheet';
		$vars['site_id'] = ee()->input->post('site_id');
		$vars['filedirs'] = ee()->input->post('filedirs');
		$vars['multi'] = (ee()->input->post('multi') == 'y');
		$vars['footer'] = true;

		ee()->config->site_prefs('', ee()->input->post('site_id'));
		exit (ee()->load->view('filemanager/filemanager', $vars, TRUE));
	}

	/**
	 * Get Selected Files
	 * Called from field.js when a new file(s) is selected
	 */
	function get_selected_files()
	{
		ee()->load->library('javascript');

		$ids = ee()->input->post('file_id');

		$files = array();
		if (! is_array($ids))
		{
			$ids = array($ids);
		}

		foreach ($ids as $id)
		{
			$file = ee()->assets_lib->get_file_by_id($id);
			if ($file)
			{
				$files[] = $file;
			}
		}

		if ($files)
		{
			$vars['helper'] = ee()->assets_lib;
			$vars['field_id']   = ee()->input->post('field_id');
			$vars['field_name'] = ee()->input->post('field_name');
			$vars['files'] = $files;

			if (ee()->input->post('view') == 'thumbs')
			{
				$vars['thumb_size']     = ee()->input->post('thumb_size');
				$vars['show_filenames'] = (ee()->input->post('show_filenames') == 'y');

				$output['html'] = ee()->load->view('thumbview/files', $vars, TRUE);
			}
			else
			{
				$vars['start_index'] = ee()->input->post('start_index');
				$vars['cols']        = ee()->input->post('show_cols');

				$output['html'] = ee()->load->view('listview/files', $vars, TRUE);
			}
		}
		else
		{
			$output['html'] = '';
		}

		// Return any thumb CSS queued up by the field
		$output['css'] = Assets_helper::get_queued_css();

		// pass back the requestId so the JS knows the response matches the request
		$output['requestId'] = ee()->input->post('requestId');

		exit(Assets_helper::get_json($output));
	}

	/**
	 * Returns true if current user can modify the filedir
	 * @param $filedir_id
	 * @return bool
	 */
	private function _can_user_access_filedir($filedir_id)
	{
		// logged in, not guest, has access
		return
			ee()->session->userdata('group_id')
			&& ee()->session->userdata('member_id')
			&& count(ee()->db->get_where('upload_no_access', array('upload_id' => $filedir_id, 'member_group' => ee()->session->userdata('group_id')))->result()) == 0;
	}

	// -------------------------------------------
	//  Update Indexes
	// -------------------------------------------

	/**
	 * Update Indexes page
	 */
	function update_indexes()
	{
		if (!$this->_allow_full_indexing())
		{
			$this->_forbidden();
		}

		$this->_set_page_title(lang('update_indexes'));

		$vars['source_list'] = ee()->assets_lib->get_all_sources();

		$js = "var Assets = {};" . Assets_helper::get_actions_js() . "\n" . 'Assets.siteUrl = "'.Assets_helper::get_site_url(). '";' . "\n" .
			Assets_helper::get_lang_js('index_complete', 'index_stale_entries_message', 'index_folders', 'index_files', '_delete');
		Assets_helper::insert_js($js);
		Assets_helper::include_garnish();
		Assets_helper::include_js('assets.js', 'settings.js');
		Assets_helper::include_css('settings.css');

		return ee()->load->view('mcp/update_indexes', $vars, TRUE);
	}

	/**
	 * Get a new index session
	 */
	function get_session_id()
	{
		ee()->load->library('javascript');
		$session = ee()->assets_lib->init_new_index_session();
		exit(Assets_helper::get_json(array('session' => $session)));
	}

	/**
	 * Start the indexing
	 */
	function start_index()
	{
		ee()->load->library('javascript');
		$session = ee()->input->post('session');
		$source = ee()->input->post('source');
		$folder_id = ee()->input->post('folder_id');
		if (empty($session) OR (empty($source) && empty($folder_id)))
		{
			exit();
		}

		if (empty($folder_id))
		{
			$output = ee()->assets_lib->get_index_list_for_source($session, $source);
		}
		else
		{
			$output = ee()->assets_lib->get_index_list_for_folder($session, $folder_id);
		}

		exit(Assets_helper::get_json($output));

	}

	/**
	* Perform Index
	*/
	function perform_index()
	{
		ee()->load->library('javascript');
		$session_id = ee()->input->post('session');
		$source_type = ee()->input->post('source_type');
		$source_id = ee()->input->post('source_id');
		$offset = ee()->input->post('offset');
		$folder_id = ee()->input->post('folder_id');

		try
		{
			if (!empty($folder_id))
			{
				$folder_row = ee()->assets_lib->get_folder_row_by_id($folder_id);
				$source_type = $folder_row->source_type;
				if ($source_type == 'ee')
				{
					$source_id = $folder_row->filedir_id;
				}
				else
				{
					$source_id = $folder_row->source_id;
				}
			}
			$output = ee()->assets_lib->perform_index($session_id, $source_type, $source_id, $offset);
		}
		catch (Exception $exception)
		{
			$output = array('error' => $exception->getMessage());
		}

		exit(Assets_helper::get_json($output));
	}

	/**
	 * Finish indexing
	 */
	public function finish_index()
	{

		ee()->load->library('javascript');
		$sources = ee()->input->post('sources');
		$command = ee()->input->post('command');
		$session_id = ee()->input->post('session');

		try
		{
			if ( !empty($sources))
			{
				$sources = explode(",", $sources);
				$source_list = array();
				foreach ($sources as $source_info)
				{
					$source_parts = explode("_", $source_info);
					$source_list[] = array('source_type' => $source_parts[0], 'source_id' => $source_parts[1]);
				}
				$output = ee()->assets_lib->finish_index($session_id, $source_list, $command);
			}
			else
			{
				$output = ee()->assets_lib->finish_index($session_id, $sources, $command);
			}
		}
		catch (Exception $exception)
		{
			$output = array('error' => $exception->getMessage());
		}

		exit(Assets_helper::get_json($output));
	}

	// -------------------------------------------
	//  File Sources
	// -------------------------------------------

	/**
	 * File Sources page
	 */
	function sources()
	{
		if (ee()->session->userdata['group_id'] != 1)
		{
			$this->_forbidden();
		}

		$this->_set_page_title(lang('manage_sources'));

		$vars['edit_source_action'] = $this->eeharbor->moduleURL('edit_source');
		$vars['delete_source_action'] = $this->eeharbor->moduleURL('delete_source');
		$vars['themes_dir'] = $this->eeharbor->getAddonThemesDir();

		$vars['sources'] = array();

		$sources = ee()->db->order_by('name', 'ASC')->get('assets_sources')->result();
		foreach ($sources as $source)
		{
			$vars['sources'][] = $source;
		}

		ee()->load->library('table');

		$js = "var Assets = {};" . Assets_helper::get_actions_js() . "\n" . 'Assets.siteUrl = "'.Assets_helper::get_site_url(). '";' . "\n" .
		Assets_helper::get_lang_js('confirm_delete_source');
		Assets_helper::insert_js($js);
		Assets_helper::include_garnish();
		Assets_helper::include_js('assets.js', 'settings.js');

		return ee()->load->view('mcp/sources', $vars, TRUE);
	}

	/**
	 * Edit or add a source
	 */
	public function edit_source()
	{
		if (ee()->session->userdata['group_id'] != 1)
		{
			$this->_forbidden();
		}

		ee()->load->library('table');
		$source_id = ee()->input->get('source_id');

		$source = NULL;
		if (!empty($source_id) && is_numeric($source_id))
		{
			try
			{
				$source_row = ee()->assets_lib->get_source_row_by_id($source_id);
				$source = ee()->assets_lib->instantiate_source_type((object) array('source_type' => $source_row->source_type, 'source_id' => $source_id));

				$this->_set_page_title(lang('edit_source'));
			}
			catch (Exception $exception)
			{
				// not loaded, $source remains null
			}
		}
		else
		{
			$this->_set_page_title(lang('add_new_source'));
		}

		$vars = array();
		$vars['is_new'] = is_null($source);
		$vars['source'] = $source;
		$vars['base'] = $this->eeharbor->getBaseURL();
		$vars['action_url'] = $this->eeharbor->moduleURL('save_source');
		$vars['source_types'] = ee()->assets_lib->get_all_source_types();
		$vars['setting_fields'] = ee()->assets_lib->get_source_settings_field_list();

		$js = "var Assets = {};" . Assets_helper::get_actions_js() . "\n" . 'Assets.siteUrl = "'.Assets_helper::get_site_url(). '";';
		$js .= Assets_helper::get_lang_js('rs_select_region');
		Assets_helper::insert_js($js);
		Assets_helper::include_garnish();
		Assets_helper::include_js('assets.js', 'settings.js');
		Assets_helper::include_css('settings.css');
		Assets_helper::include_css('assets.css');

		return ee()->load->view('mcp/edit_source', $vars, TRUE);
	}

	/**
	 * Save source
	 */
	public function save_source()
	{
		if (ee()->session->userdata['group_id'] != 1)
		{
			$this->_forbidden();
		}

		$name = ee()->input->post('source_name');

		if ( !empty($name))
		{

			$name = htmlentities($name);

			$save_data = array();
			$field_list = ee()->assets_lib->get_source_settings_field_list();
			$source_type = ee()->input->post('source_type');

			foreach ($field_list[$source_type] as $field)
			{
				$save_data[$field] = ee()->input->post($source_type . '_' . $field);
			}

			switch ($source_type)
			{
				case 's3':
				{
					$save_data['bucket'] = ee()->input->post('s3_bucket');
					$save_data['url_prefix'] = ee()->input->post('s3_bucket_url_prefix');
					$save_data['location'] = ee()->input->post('s3_bucket_location');
					$save_data['cf_distribution'] = ee()->input->post('cf_distribution');

					$save_data['cache_amount'] = (int) ee()->input->post('s3_cache_amount');
					$period = ee()->input->post('s3_cache_period');
					$save_data['cache_period'] = preg_match('/seconds|minutes|hours|days/', $period) ? $period : '';
					break;
				}
				case 'gc':
				{
					$save_data['bucket'] = ee()->input->post('gc_bucket');
					$save_data['url_prefix'] = ee()->input->post('gc_bucket_url_prefix');

					$save_data['cache_amount'] = (int) ee()->input->post('gc_cache_amount');
					$period = ee()->input->post('gc_cache_period');
					$save_data['cache_period'] = preg_match('/seconds|minutes|hours|days/', $period) ? $period : '';
					break;
				}
				case 'rs':
				{
					$save_data['region'] = ee()->input->post('rs_region');
					$save_data['container'] = ee()->input->post('rs_container');
					$save_data['url_prefix'] = ee()->input->post('rs_container_url_prefix');
				}
			}


			$source_id = ee()->input->post('source_id');

			$data = array(
				'name' => $name,
				'settings' => Assets_helper::get_json($save_data)
			);

			ee()->assets_lib->store_source($source_type, $source_id, $data);

			// change the name for the top level folder as well
			ee()->assets_lib->rename_source_folder($source_id, $source_type, $name);

			$this->eeharbor->flashData('message_success', lang('source_saved'));
		}

		ee()->functions->redirect($this->eeharbor->moduleURL('sources'));
	}

	/**
	 * Delete a source
	 */
	public function delete_source()
	{
		if (ee()->session->userdata['group_id'] != 1)
		{
			$this->_forbidden();
		}

		$source_id = ee()->input->post('source_id');
		ee()->assets_lib->delete_source($source_id);
		$this->eeharbor->flashData('message_success', lang('source_deleted'));
		ee()->functions->redirect($this->eeharbor->moduleURL('sources'));
	}

	/**
	 * Get S3 buckets
	 */
	public function get_s3_buckets()
	{
		if (ee()->session->userdata['group_id'] != 1)
		{
			$this->_forbidden();
		}

		ee()->load->library('table');
		ee()->load->helper('form_helper');

		$key_id = ee()->input->post('s3_access_key_id');
		$secret_key = ee()->input->post('s3_secret_access_key');
		$existing_source = ee()->input->post('source_id');

		$bucket_settings = array();

		// if source id is passed along, load the chosen settings for buckets
		if ($existing_source)
		{
			try
			{
				$source = ee()->assets_lib->instantiate_source_type((object) array('source_type' => 's3', 'source_id' => $existing_source));
			}
			catch (Exception $exception)
			{
				exit ($exception->getMessage());
			}

			$bucket_settings = $source->settings();
		}

		require_once PATH_THIRD . 'assets/sources/s3/source.s3.php';

		$vars = array();

		try
		{
			// pass along the chosen settings (if any) for pre-selecting enabled buckets
			$vars['bucket_list'] = Assets_s3_source::get_bucket_list($key_id, $secret_key);
		}
		catch (Exception $exception)
		{
			exit ($exception->getMessage());
		}

		// here, have some prefilled settings
		if (empty($bucket_settings) && !empty($vars['bucket_list']))
		{
			$bucket_settings = reset($vars['bucket_list']);
		}

		$vars['source_settings'] = $bucket_settings;

		exit (ee()->load->view('mcp/components/s3_buckets', $vars, TRUE));
	}

	/**
	 * Get S3 buckets
	 */
	public function get_gc_buckets()
	{
		if (ee()->session->userdata['group_id'] != 1)
		{
			$this->_forbidden();
		}

		ee()->load->library('table');
		ee()->load->helper('form_helper');

		$key_id = ee()->input->post('gc_access_key_id');
		$secret_key = ee()->input->post('gc_secret_access_key');
		$existing_source = ee()->input->post('source_id');

		$bucket_settings = array();

		// if source id is passed along, load the chosen settings for buckets
		if ($existing_source)
		{
			try
			{
				$source = ee()->assets_lib->instantiate_source_type((object) array('source_type' => 'gc', 'source_id' => $existing_source));
			}
			catch (Exception $exception)
			{
				exit ($exception->getMessage());
			}

			$bucket_settings = $source->settings();
		}

		require_once PATH_THIRD . 'assets/sources/gc/source.gc.php';

		$vars = array();

		try
		{
			// pass along the chosen settings (if any) for pre-selecting enabled buckets
			$vars['bucket_list'] = Assets_gc_source::get_bucket_list($key_id, $secret_key);
		}
		catch (Exception $exception)
		{
			exit ($exception->getMessage());
		}

		// here, have some prefilled settings
		if (empty($bucket_settings) && !empty($vars['bucket_list']))
		{
			$bucket_settings = reset($vars['bucket_list']);
		}

		$vars['source_settings'] = $bucket_settings;

		exit (ee()->load->view('mcp/components/gc_buckets', $vars, TRUE));
	}

	/**
	 * Get Rackspace Cloud containers
	 */
	public function get_rs_regions()
	{
		if (ee()->session->userdata['group_id'] != 1)
		{
			$this->_forbidden();
		}

		$existing_source = ee()->input->post('source_id');
		$username = ee()->input->post('rs_username');
		$api_key = ee()->input->post('rs_api_key');

		require_once PATH_THIRD . 'assets/sources/rs/source.rs.php';

		$settings = (object) array('username' => $username, 'api_key' => $api_key);
		$source = new Assets_rs_source($existing_source, $settings, true);
		$vars['region_list'] = $source->get_region_list();


		// here, have some prefilled settings
		if (empty($region_settings) && !empty($vars['region_list']))
		{
			$region_settings = reset($vars['region_list']);
		}

		$vars['source_settings'] = $region_settings;

		exit (ee()->load->view('mcp/components/rs_regions', $vars, TRUE));
	}

	/**
	 * Get Rackspace Cloud containers
	 */
	public function get_rs_containers()
	{
		if (ee()->session->userdata['group_id'] != 1)
		{
			$this->_forbidden();
		}

		ee()->load->library('table');
		ee()->load->helper('form_helper');

		$username = ee()->input->post('rs_username');
		$api_key = ee()->input->post('rs_api_key');
		$region = ee()->input->post('rs_region');
		$existing_source = ee()->input->post('source_id');

		$container_settings = array();

		// if source id is passed along, load the chosen settings for container
		if ($existing_source)
		{
			try
			{
				$source = ee()->assets_lib->instantiate_source_type((object) array('source_type' => 'rs', 'source_id' => $existing_source));
			}
			catch (Exception $exception)
			{
				exit ($exception->getMessage());
			}

			$container_settings = $source->settings();
		}

		require_once PATH_THIRD . 'assets/sources/rs/source.rs.php';

		$vars = array();

		try
		{

			// pass along the chosen settings (if any) for pre-selecting containers
			$settings = (object) array('username' => $username, 'api_key' => $api_key, 'region' => $region);
			$source = new Assets_rs_source($existing_source, $settings, true);
			$vars['container_list'] = $source->get_container_list();
		}
		catch (Exception $exception)
		{
			exit ($exception->getMessage());
		}

		// here, have some prefilled settings
		if (empty($container_settings) && !empty($vars['container_list']))
		{
			$container_settings = reset($vars['container_list']);
		}

		$vars['source_settings'] = $container_settings;

		exit (ee()->load->view('mcp/components/rs_containers', $vars, TRUE));
	}

	// -------------------------------------------
	//  Settings
	// -------------------------------------------

	/**
	 * Settings page
	 */
	function settings()
	{
		if (ee()->session->userdata['group_id'] != 1)
		{
			$this->_forbidden();
		}

		$this->_set_page_title(lang('settings'));

		$vars['action_url'] = $this->eeharbor->moduleURL('save_settings');
		$vars['settings'] = Assets_helper::get_global_settings();

		ee()->load->library('table');

		return ee()->load->view('mcp/settings', $vars, TRUE);
	}

	/**
	 * Save Settings
	 */
	function save_settings()
	{
		if (ee()->session->userdata['group_id'] != 1)
		{
			$this->_forbidden();
		}

		$settings = ee()->input->post('settings');

		$data['settings'] = base64_encode(serialize($settings));

		ee()->db->where('name', 'assets')
		             ->update('fieldtypes', $data);

		// redirect to Index
		$this->eeharbor->flashData('message_success', lang('global_settings_saved'));
		ee()->functions->redirect($this->eeharbor->moduleURL('settings'));
	}

	/**
	 * Handle access restriction
	 */
	private function _forbidden()
	{
		header('HTTP/1.1 403 Forbidden');
		exit();
	}

	public function showNav()
	{
		//  CP-only stuff
		if (REQ == 'CP')
		{
			$nav[lang('file_manager')] = '/';

			if ($this->_allow_full_indexing())
			{
				$nav[lang('update_indexes')] = 'update_indexes';
			}

			// Set the right nav for Super Admins
			if (ee()->session->userdata['group_id'] == 1)
			{
				$nav[lang('manage_sources')] = 'sources';
				$nav[lang('settings')] = 'settings';
			}

			$this->eeharbor->getNav($nav);
		}
		else
		{
			// disable the output profiler
			ee()->output->enable_profiler(FALSE);
		}
	}

	/**
	 * Returns true if full indexing is allowed for current user
	 *
	 * @return bool
	 */
	private function _allow_full_indexing()
	{
		return ee()->session->userdata['group_id'] == 1 || in_array(ee()->config->item('assets_allow_indexing'), array("y", "yes", 1));
	}
}
