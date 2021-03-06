<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Assets Google Cloud File
 *
 * @package   Assets
 * @author    EEHarbor <help@eeharbor.com>
 * @copyright Copyright (c) 2016 EEHarbor
 */
class Assets_gc_file extends Assets_base_file
{
	/**
	 * @var Assets_gc_source
	 */
	protected $source;

	/**
	 * File information from the GC servers
	 * @var StdClass
	 */
	private $_file_info;

	/**
	 * @var array|bool|mixed|null
	 */
	private $_source_settings;

	/**
	 * Construct the asset file from asset id and source
	 * @param $file_id
	 * @param Assets_gc_source $source
	 * @param $prefetched_row if passed, will be used instead of loading DB data
	 */
	public function __construct($file_id, Assets_gc_source $source, $prefetched_row = null)
	{
		parent::__construct($file_id, $source, $prefetched_row);

		$bucket_id = $this->folder_row->source_id;

		$this->_source_settings = $source->get_source_settings();
		$this->subpath = $this->folder_row->full_path . $this->row_field('file_name');
		$this->path = $this->_source_settings->bucket . '/' . $this->subpath;
		$this->server_path = trim($this->subpath, '/');
	}

	/**
	 * Does file exist?
	 *
	 * @return bool
	 */
	public function exists()
	{
		// checking would be too expensive, so we'll just be optimistic
		return TRUE;
	}

	/**
	 * Return file folder
	 *
	 * @return string
	 */
	public function folder()
	{
		$path = $this->_source_settings->bucket . ($this->subpath ? '/'.$this->subpath : '');
		return pathinfo($path, PATHINFO_DIRNAME);
	}

	/**
	 * Return the file URL
	 *
	 * @param string $manipulation_name
	 * @return string
	 */
	public function url($manipulation_name = '')
	{
		if ( ! empty($manipulation_name))
		{
			return NULL;
		}

		$prefix = !empty($this->_source_settings->subfolder) ? rtrim($this->_source_settings->subfolder, '/').'/' : '';

		$expires = !empty($this->_source_settings->cache_amount) && !empty($this->_source_settings->cache_period);
		$expires = $expires && is_numeric($this->_source_settings->cache_amount) && preg_match('/seconds|minutes|hours|days/', $this->_source_settings->cache_period);

		if ($expires)
		{
			$cache_buster = '?mtime='.$this->date_modified();
		}
		else
		{
			$cache_buster = '';
		}


		return $this->_source_settings->url_prefix . $prefix . $this->subpath . $cache_buster;
	}

	/**
	 * Returns a local copy of the file
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function get_local_copy()
	{
		$location = Assets_helper::get_temp_file();

		$prefix = !empty($this->_source_settings->subfolder) ? rtrim($this->_source_settings->subfolder, '/').'/' : '';

		@$this->source->GC->getObject($this->_source_settings->bucket, $prefix.$this->subpath, $location);

		if (!filesize($location))
		{
			throw new Exception(lang('couldnt_download'));
		}
		return $location;
	}

	/**
	 * Returns the server path for the file
	 *
	 * @param $manipulation_name
	 * @return mixed
	 */
	public function server_path($manipulation_name = '')
	{
		if ( ! empty($manipulation_name))
		{
			return NULL;
		}

		return $this->server_path;
	}

	/**
	 * Subfolder
	 */
	public function subfolder($manipulation_name = '')
	{
		if ( ! empty($manipulation_name))
		{
			return NULL;
		}

		if (! isset($this->subfolder))
		{
			$this->subfolder = dirname($this->subpath);
			if ($this->subfolder == '.') $this->subfolder = '';
		}

		return $this->subfolder;
	}

	/**
	 * File Size
	 */
	function size($manipulation_name = '', $fresh_data = FALSE)
	{
		if ( ! empty($manipulation_name))
		{
			return NULL;
		}

		if ($fresh_data)
		{
			return $this->_get_file_info()->size;
		}
		return $this->row_field('size');
	}

	/**
	 * @param bool $fresh_data
	 * @return mixed|string
	 */
	public function date_modified($fresh_data = FALSE)
	{
		if ($fresh_data)
		{
			return $this->_get_file_info()->time;
		}
		return $this->row_field('date_modified');
	}


	/**
	 * Returns a path for the thumbnail source
	 * @return mixed
	 */
	public function get_thumbnail_source_path()
	{
		$path = Assets_helper::ensure_cache_path('assets/gc_sources') . $this->file_id . '.jpg';
		if (!file_exists($path))
		{
			$location = $this->get_local_copy();
			@rename($location, $path);
		}

		return Assets_helper::ensure_cache_path('assets/gc_sources') . $this->file_id . '.jpg';
	}

	private function _get_file_info()
	{
		if (empty($this->_file_info))
		{
			$prefix = !empty($this->_source_settings->subfolder) ? rtrim($this->_source_settings->subfolder, '/').'/' : '';
			$this->_file_info = (object) $this->source->GC->getObjectInfo($this->_source_settings->bucket, $prefix.$this->subpath);
		}
		return $this->_file_info;
	}
}
