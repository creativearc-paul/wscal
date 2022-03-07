<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

include_once('eeharbor.php');

/**
 * Assets Module
 *
 * @package   Assets
 * @author    EEHarbor <help@eeharbor.com>
 * @copyright Copyright (c) 2016 EEHarbor
 */
class Assets
{
	public function __construct()
	{
		$this->eeharbor = new \assets\EEHarbor;
		ee()->load->add_package_path(PATH_THIRD.'assets/');
		ee()->load->library('assets_lib');
	}

	/**
	 * Get files and parse them
	 */
	public function files()
	{
		$tagdata = ee()->TMPL->tagdata;

		// Ignore if there's no tagdata
		if (!$tagdata)
		{
			return '';
		}

		$parameters = $this->_gather_file_parameters();

		$files = ee()->assets_lib->get_files($parameters);

		if ($files)
		{
			// is there a var_prefix?
			if (($var_prefix = ee()->TMPL->fetch_param('var_prefix')) !== FALSE)
			{
				$var_prefix = rtrim($var_prefix, ':').':';
				$tagdata = str_replace($var_prefix, '', $tagdata);
			}

			return Assets_helper::parse_file_tag($files, $tagdata);
		}
		else
		{
			return ee()->TMPL->no_results();
		}
	}

	/**
	 * Get folders.
	 *
	 * @param array $passed_parameters if this is passed, will not bother looking up template parameters
	 * @param int $depth depth of this lookup
	 * @return string
	 */
	public function folders($passed_parameters = array(), $depth = 0)
	{
		$tagdata = ee()->TMPL->tagdata;

		// Ignore if there's no tagdata
		if (!$tagdata)
		{
			return '';
		}

		// Load template parameters, if none have been passed
		$parameters = empty($passed_parameters) ? $this->_gather_folder_parameters() : $passed_parameters;

		if (empty($parameters))
		{
			return '';
		}

		$folders = ee()->assets_lib->get_folders($parameters);

		// Avoid unnecessary actions
		$load_subfolders = FALSE;
		if (strpos($tagdata, '{subfolders}') !== FALSE)
		{
			$load_subfolders = TRUE;
		}

		// Make sure there are folders
		if ($folders)
		{
			$results = array();

			foreach ($folders as $folder)
			{
				$subfolders = '';
				// See if we have subfolders
				if ($load_subfolders && ee()->assets_lib->get_folder_id_by_params(array('parent_id' => $folder->folder_id)))
				{
					$subfolder_parameters['parent_id'] = $folder->folder_id;
					$subfolders = $this->folders($subfolder_parameters, $depth + 1);
				}
				$results[] = array(
					'folder_name' => $folder->folder_name,
					'folder_id' => $folder->folder_id,
					'parent_id' => $folder->parent_id,
					'subfolders' => $subfolders,
					'depth' => $depth,
					'total_subfolders' => ee()->assets_lib->get_subfolder_count($folder->folder_id)
				);
			}

			// is there a var_prefix?
			if (($var_prefix = ee()->TMPL->fetch_param('var_prefix')) !== FALSE)
			{
				$var_prefix = rtrim($var_prefix, ':').':';
				$tagdata = str_replace($var_prefix, '', $tagdata);
			}

			return ee()->TMPL->parse_variables($tagdata, $results);
		}
		else
		{
			return ee()->TMPL->no_results();
		}
	}

	/**
	 * Get information about a single folder.
	 *
	 * @return string
	 */
	public function folder()
	{
		$tagdata = ee()->TMPL->tagdata;

		// Ignore if there's no tagdata
		if (!$tagdata)
		{
			return '';
		}

		$folder_id = ee()->TMPL->fetch_param('folder_id');

		if (empty($folder_id))
		{
			$folder = ee()->TMPL->fetch_param('folder');

			if (empty($folder) OR $folder == 'top')
			{
				$folder_id = 0;
			}
			else
			{
				$folder_id = $this->_get_folder_id_by_tagpath($folder);

			}
		}

		if ( !$folder_id)
		{
			return "";
		}

		$folder = ee()->assets_lib->get_folder_row_by_id($folder_id);
		if (!$folder)
		{
			return ee()->TMPL->no_results();
		}

		$results = array(array(
			'folder_name' => $folder->folder_name,
			'folder_id' => $folder->folder_id,
			'parent_id' => $folder->parent_id
		));

		// is there a var_prefix?
		if (($var_prefix = ee()->TMPL->fetch_param('var_prefix')) !== FALSE)
		{
			$var_prefix = rtrim($var_prefix, ':').':';
			$tagdata = str_replace($var_prefix, '', $tagdata);
		}

		return ee()->TMPL->parse_variables($tagdata, $results);
	}

	/**
	 * Return the number of total folders by parameters.
	 *
	 * @return int
	 */
	public function total_folders()
	{
		$parameters = $this->_gather_folder_parameters();
		$folders = ee()->assets_lib->get_folders($parameters);
		return count($folders);
	}

	/**
	 * Return the number of total files by parameters.
	 *
	 * @return int
	 */
	public function total_files()
	{
		$parameters = $this->_gather_file_parameters();
		$files = ee()->assets_lib->get_files($parameters);
		return count($files);
	}

	/**
	 * Gather file parameters from the template.
	 *
	 * @return array
	 */
	private function _gather_file_parameters()
	{
		$folders = ee()->TMPL->fetch_param('folder');
		if ($folders == "any" || $folders == "*")
		{
			$folders = array(':any:');
		}
		else
		{
			$folders = preg_split("/\|/", $folders);
			foreach ($folders as &$folder)
			{
				$folder = $this->_get_folder_id_by_tagpath($folder);
			}

			$folder_ids = ee()->TMPL->fetch_param('folder_id');
			$folder_ids = preg_split("/\|/", $folder_ids);

			$folders = array_merge($folders, $folder_ids);
		}

		$file_ids = ee()->TMPL->fetch_param('file_id');
		$file_ids = preg_split("/\|/", $file_ids);

		// sort out required kinds if any
		$kinds = ee()->TMPL->fetch_param('kind', '');
		$kinds = empty($kinds) ? 'any' : $kinds;

		if ($kinds != 'any' && ! is_array($kinds))
		{
			$kinds = preg_split("/\||&&/", $kinds);
		}

		$orderby = ee()->TMPL->fetch_param('orderby', '');

		$fixed_order = ee()->TMPL->fetch_param('fixed_order');
		if (!empty($fixed_order))
		{
			$fixed_order = preg_split("/\|/", $fixed_order);
			$file_ids = $fixed_order;
			$orderby = 'fixed';
		}

		$keywords = (string) ee()->TMPL->fetch_param('keywords', '');
		$keyword_array = array_filter(preg_split("/\||&&/", (string) ee()->TMPL->fetch_param('keywords', '')));
		if (strpos($keywords, '&&') !== FALSE)
		{
			array_unshift($keyword_array, '&&and');
		}
		else
		{
			array_unshift($keyword_array, '||or');
		}

		$parameters = array(
			'folders' => $folders,
			'keywords' => $keyword_array,
			'orderby' => $orderby,
			'sort' => ee()->TMPL->fetch_param('sort', ''),
			'offset' => ee()->TMPL->fetch_param('offset', 0),
			'limit' => ee()->TMPL->fetch_param('limit', 100),
			'kinds' => $kinds,
			'file_ids' => $file_ids,
		);

		return $parameters;
	}

	/**
	 * Gather folder parameters from the template.
	 *
	 * @return array
	 */
	private function _gather_folder_parameters()
	{
		$folder_id = ee()->TMPL->fetch_param('parent_id');

		if (empty($folder_id))
		{
			$folder = ee()->TMPL->fetch_param('parent_folder');

			if (empty($folder) OR $folder == 'top')
			{
				$folder_id = 0;
			}
			else
			{
				$folder_id = $this->_get_folder_id_by_tagpath($folder);

				// If no folder found by designated parameter, return.
				if ( !$folder_id)
				{
					return array();
				}
			}
		}

		$parameters = array(
			'parent_id' => $folder_id,
			'keywords' => array_filter(preg_split("/\||&&/", ee()->TMPL->fetch_param('keywords', ''))),
			'offset' => ee()->TMPL->fetch_param('offset', 0),
			'limit' => ee()->TMPL->fetch_param('limit', 100),
			'recursive' => ee()->TMPL->fetch_param('recursive', 'no'),
			'sort' => ee()->TMPL->fetch_param('sort', 'asc'),
		);

		return $parameters;
	}

	/**
	 * Get folder id by tag path
	 * @param $tagpath
	 * @return bool
	 */
	private function _get_folder_id_by_tagpath($tagpath)
	{
		$folder_id = FALSE;
		$pattern = '/\{(filedir|source)_([0-9]+)\}([a-z0-9_\- \/]+)?/i';

		if (preg_match($pattern, $tagpath, $matches))
		{
			try
			{
				$source_type = $matches[1];
				$source_id = $matches[2];
				$path = isset($matches[3]) ? $matches[3] : '';

				$field = 'source_id';
				switch($source_type)
				{
					case 'filedir':
						// check if filedir exists for this site
						if (!ee()->db->get_where('upload_prefs', array('id' => $source_id, 'site_id' => intval(ee()->config->item('site_id'))))->row())
						{

							return FALSE;
						}
						$field = 'filedir_id';
						break;

					case 'source':
						if (!ee()->db->get_where('assets_sources', array('source_id' => $source_id)))
						{
							return FALSE;
						}
						break;
				}

				if (!empty($path))
				{
					$path = rtrim($path, '/') . '/';
				}

				$folder_row = ee()->db->get_where('assets_folders', array($field => $source_id, 'full_path' => $path))->row();
				if (empty($folder_row))
				{
					$folder_id = FALSE;
				}
				else
				{
					$folder_id = $folder_row->folder_id;
				}

			}
			catch (Exception $exception)
			{
				$folder_id = FALSE;
			}
		}

		return $folder_id;
	}
}
