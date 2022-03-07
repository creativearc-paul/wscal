<?php
namespace assets;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'assets/helpers/eeharbor_abstracted.php';

/**
 * EEHarbor helper class
 *
 * Bridges the functionality gaps between EE versions.
 *
 * @package			eeharbor_helper
 * @version			1.4.3
 * @author			Tom Jaeger <Tom@EEHarbor.com>
 * @link			https://eeharbor.com
 * @copyright		Copyright (c) 2016, Tom Jaeger/EEHarbor
 */

// --------------------------------------------------------------------

class EEHelper extends \assets\EEHarbor_abstracted {

	private $_module;
	private $_module_name;
	private $_ee_major_version;
	private $app_settings;

	/**
	 * Foundation function
	 * Determines EE version to:
	 *  - Set up right nav or sidebar
	 *  - Determine base_url from raw or CP/URL service
	 *  - Set view folder
	 * Includes any JS or CSS from themes.
	 */
	public function __construct($info)
	{
		$this->_module = $info['module'];
		$this->_module_name = $info['module_name'];
	}

	public function instantiate($which)
	{
		ee()->legacy_api->instantiate($which);
	}

	public function getBaseURL($method='', $extra='')
	{
		if($method == '/') $method = '';
		elseif($method) $method = '/'.$method;

		return ee('CP/URL', 'addons/settings/'.$this->_module.$method.$extra);
	}

	/**
	 * [getNav description]
	 * @param  array  $nav_items array('item name' => 'url')
	 * @param  array  $buttons   array('item name' => array('new' => 'link'))
	 * @return [type]            [description]
	 */
	public function getNav($nav_items=array(), $buttons=array())
	{
		$sidebar = ee('CP/Sidebar')->make();
		$last_segment = ee()->uri->segment_array();
		$last_segment = end($last_segment);

		foreach($nav_items as $title => $method) {
			if($method == '/') $method = 'index';

			if(strpos($method, 'http') === false) $url = $this->getBaseURL($method);
			else $url = $method;

			$nav_items[$title] = $sidebar->addHeader($title)->withUrl($url);

			// This is to add buttons to a nav.
			if(array_key_exists($title, $buttons))
			{
				foreach($buttons[$title] as $text => $url) {
					$nav_items[$title]->withButton($text, $this->moduleURL($url));
				}
			}

			if($last_segment == $method || ($method == 'index' && $last_segment == $this->_module)) $nav_items[$title]->isActive();
		}
	}

	public function cpURL($path, $mode='', $variables=array())
	{
		if($mode) $mode = '/'.$mode;

		if($path == 'listing') {
			$path = 'publish';
		}

		if($path == 'publish') {
			if($mode == '/create' && isset($variables['channel_id'])) {
				$mode .= '/'.$variables['channel_id'];
				unset($variables['channel_id']);
			} elseif($mode == '/edit' && isset($variables['entry_id'])) {
				$mode .= '/entry/'.$variables['entry_id'];
				unset($variables['entry_id']);
			}
		}

		$url = ee('CP/URL')->make($path.$mode, $variables);

		return $url;
	}

	public function moduleURL($method='index', $variables=array())
	{
		$url = ee('CP/URL')->make('addons/settings/'.$this->_module.'/'.$method, $variables);

		return $url;
	}

	public function view($view, $vars = array(), $return = FALSE)
	{
		if(!isset($vars['base_url'])) $vars['base_url'] = $this->getBaseURL();
		if(!isset($vars['cp_page_title'])) $vars['cp_page_title'] = ee()->view->cp_page_title;

		return array(
			'heading' => ee()->view->cp_page_title,
			'breadcrumb' => array(
				ee('CP/URL', 'addons/settings/'.$this->_module.'/')->compile() => $this->_module_name
				),
			'body' => ee('View')->make($this->_module.':'.$view)->render($vars)
		);
	}

	public function getCurrentPage($options = array())
	{
		//TODO: implement the passed $options variable (if it exists)
		if(ee()->input->get('per_page', 1)) return ee()->input->get('per_page', 1);
		elseif(ee()->input->get('page', 1)) return ee()->input->get('page', 1);
		else return 1;
	}

	public function getStartNum($options)
	{
		return ($options['current_page'] * $options['per_page']) - $options['per_page'];
	}

	public function pagination($options = array())
	{
		$pagination = ee('CP/Pagination', $options['total_rows'])
						->perPage($options['per_page'])
						->currentPage($options['current_page'])
						->render($options['base_url']);

		return $pagination;
	}

	public function getSettings($asArray = false)
	{
		if(! $this->settings_initiated())
		{
			$this->initiateSettings();
		}

		if($asArray) return $this->app_settings;

		return (object) $this->app_settings;

	}

	public function getConfig($item)
	{
		if(! $this->settings_initiated())
		{
			$this->initiateSettings();
		}

		return $this->app_settings[$item];
	}

	public function setConfig($item, $value)
	{
		// EE caches the list of DB tables, so unset the table_names var if it's set
		// otherwise table_exists could return a false negative if it was just created.
		if(isset(ee()->db->data_cache['table_names'])) unset(ee()->db->data_cache['table_names']);

		// Make sure the settings table exists.
		if(ee()->db->table_exists($this->_module.'_settings')) {
			// Find out if the settings exist, if not, insert them.
			ee()->db->where('site_id', ee()->config->item('site_id'));
			$exists = ee()->db->count_all_results($this->_module.'_settings');

			$data['site_id'] = ee()->config->item('site_id');
			$data[$item] = $value;

			if($exists) {
				ee()->db->where('site_id', ee()->config->item('site_id'));
				ee()->db->update($this->_module.'_settings', $data);
			} else {
				ee()->db->insert($this->_module.'_settings', $data);
			}
		}

		// Set variables
		$this->app_settings[$item] = $value;
		ee()->session->set_cache($this->_module, "settings", $this->app_settings);
	}

	public function cache($mode, $key = false, $data = false, $persistent = true) {
		// Returns EE's native cache function for EE3.
		switch($mode) {
			case 'get':
				if($persistent) return ee()->cache->get('/'.$this->_module.'/'.$key);
				else return ee()->session->cache($this->_module, $key, false);
				break;

			case 'set':
				if($persistent) return ee()->cache->save('/'.$this->_module.'/'.$key, $data);
				else return ee()->session->set_cache($this->_module, $key, $data);
				break;

			case 'delete':
			case 'clear':
				return ee()->cache->delete('/'.$this->_module.'/'.$key);
				break;

			default:
				return false;
		}
	}

	/**
	 * Flash a message to the screen
	 * @param  string $type             Type of message to display. [message_success, message_notice, message_error, message_failure]
	 * @param  string $title            Title of flash message (Concatenated with body when EE2)
	 * @param  string $body             Title of flash message (Concatenated with title when EE2)
	 * @param  array  $extra_parameters Name of EE3 alert functions to call in addition to the default ones. (does nothing in EE2) ex. ['cannotClose']
	 */
	public function flashData($type='message_success', $title='', $body='', $extra_parameters=array()) {
		$alert = ee('CP/Alert')->make($title);

		// set alert type based on name
		if($type === "message_error" || $type === "message_failure") {
			$alert->asIssue();
		} elseif($type === "message_notice") {
			$alert->asWarning();
		} else {
			$alert->asSuccess();
		}

		// Set the alert title and body
		$alert->withTitle($title)
			->addToBody($body);

		// default to allowing alerts to close
		$alert->canClose();

		// if there are custom parameters, call them at the end
		foreach($extra_parameters as $extra)
		{
			// make sure the method exists, then call it
			if(method_exists($alert, $extra))
				$alert->$extra();
		}

		// defer alert so it actually shows up on page
		$alert->defer();
	}

	/**
	 * Gets the directory for the addon's theme files
	 * @return [string] [path of directory]
	 */
	public function getAddonThemesDir() {
		return "/themes/user/" . $this->_module . '/';
	}

	/**
	 * Overwrite any native EE Classes.
	 * EE3 uses EE's set() method.
	 *
	 * @param object $class    The EE class object you want to overwrite
	 * @param object $data     The optional data used to overwrite.
	 **/
	public function overwriteEEClass($class, $data='') {
		ee()->set($class, $data);
	}

	/**
	 * Remove any native EE Classes.
	 * EE3 uses EE's remove() method.
	 *
	 * @param object $class    The EE class object you want to overwrite
	 **/
	public function removeEEClass($class) {
		ee()->remove($class);
	}

	/**
	 * XSS protection for user input
	 * @param  String or Array $input xss_clean accepts a string or array as input.
	 * @return Sanitized string or array
	 */
	public function xss_clean($input)
	{
		return ee('Security/XSS')->clean($input);
	}

	/**
	 * Get information about the current page (in the CP)
	 * @param  [string] $options option to only get a portion of the information rather than an array
	 * @return [string or array]         full path info in array, or single element
	 */
	public function getCurrentUrlInfo($options = null)
	{
		// This is a tricky one, because if will give errors if there are not all those segments
		// Right now I am supressing those warnings
		$url = ee()->uri->uri_string();
		$segments = explode( "/", $url);
		$url_info['full'] = $url;
		$url_info['cp'] = (@$segments[0] === "cp");
		$url_info['segments'] = $segments;
		$url_info['module'] = @$segments[3];
		$url_info['method'] = array_key_exists("4", $segments) ? @$segments["4"] : "index";

		if($options && array_key_exists($options, $url_info))
			return $url_info[$options];

		return $url_info;
	}

	/**
	 * Returns the system cache path
	 * @return string - path to cache
	 */
	public function getCachePath() {
		$cache_path = ee()->config->item('cache_path');

		if (empty($cache_path))
			$cache_path = PATH_CACHE;

		return $cache_path;
	}

	/**
	 * Provides a quick boolean for checking ee version
	 * @return boolean is_ee2
	 */
	public function is_ee2() {
		return false;
	}

	/**
	 * Provides a quick boolean for checking ee version
	 * @return boolean is_ee3
	 */
	public function is_ee3() {
		return true;
	}

	/**
	 * Provides a quick boolean for checking ee version
	 * @return boolean is_ee4
	 */
	public function is_ee4() {
		return false;
	}

	/**
	 * Call the EE method for removing double slashes. Is specific to the EE version.
	 * @return string result
	 */
	public function reduce_double_slashes($string) {
		ee()->load->helper('string');
		return reduce_double_slashes($string);
	}

	/**
	 * Initiate the settings if they are not already initiated
	 * @return [Boolean] [description]
	 */
	private function initiateSettings()
	{
		// Early return if the settings have already been initiated
		if($this->settings_initiated())
		{
			return true;
		}

		// EE caches the list of DB tables, so unset the table_names var if it's set
		// otherwise table_exists could return a false negative if it was just created.
		if(isset(ee()->db->data_cache['table_names'])) unset(ee()->db->data_cache['table_names']);

		$dbSettings = array();

		if(ee()->db->table_exists($this->_module.'_settings'))
		{
			$dbSettingsQuery = ee()->db->get_where($this->_module.'_settings', array($this->_module.'_settings.site_id' => ee()->config->item('site_id')));

			if($this->_module === "structure")
			{
				foreach($dbSettingsQuery->result() as $row)
				{
					$dbSettings[$row->var] = $row->var_value;
				}
			} else {
				$dbSettings = $dbSettingsQuery->row_array();
			}
		}

		// Fieldtype settings
		$ftSettingsQuery = ee()->db->select('settings')
				->where('name', $this->_module)
				->get('fieldtypes');

		if((bool)$ftSettingsQuery->num_rows())
		{
			$ftSettings = @unserialize(@base64_decode($ftSettingsQuery->row('settings')));

			// It is possible there is actually nothing in the FT settings. So if not, just return an empty array
			if(!$ftSettings)
				$ftSettings = array();

		} else {
			$ftSettings = array();
		}

		$addonSettings = require PATH_THIRD.'assets/addon.setup.php';

		$this->app_settings = array_merge(array_merge($ftSettings, $dbSettings), $addonSettings);

		// cache the settings as an array
		if(isset(ee()->session) && method_exists(ee()->session, 'set_cache')) {
			ee()->session->set_cache($this->_module, "settings", $this->app_settings);
		}

		return true;
	}

	/**
	 * Check if the settings have been initiated
	 * @return [bool] True/False for initiated settings
	 */
	private function settings_initiated()
	{
		// Settings are initiated if this variable is set
		if(isset($this->app_settings))
		{
			return true;
		}

		if(isset(ee()->session) && method_exists(ee()->session, 'cache') && ee()->session->cache($this->_module, "settings", false))
		{
			// $this->app_settings were not set before apparently, so lets set them to the cached version
			$this->app_settings = ee()->session->cache($this->_module, "settings", false);

			return true;
		}

		return false;
	}

	/**
	 * Inject Javascript on the page
	 */
	public function javascript_to_page($js)
	{
		ee()->cp->add_to_foot('<script type="text/javascript">'.$js.'</script>');
	}

	/**
	 * Inject EEHarbor ping to page
	 */
	public function version_check($type = "php")
	{
		if($type==="js" OR $type==="javascript")
		{
			$this->version_check_js();
		} else {
			return $this->version_check_php();
		}
	}

	/**
	 * Inject EEHarbor ping to page
	 */
	public function version_check_js()
	{
		$license = $this->getLicenseKey();
		$addon = "assets";
		$version = $this->getConfig('version');
		$ee_version = $this->getEEVersion(false);
		$domain = ee()->config->config['base_url'];

		$js = "
		var post_data = {
			license: '$license',
			addon: '$addon',
			version: '$version',
			ee: '$ee_version',
			domain: '$domain'
		};

		$.ajax({
			type: 'POST',
			url: 'http://ping.eeharbor.com',
			data: post_data,
			success: function (data) {
				var parsed_data = JSON.parse(data);
				// console.log(parsed_data);
			}
		});";

		$this->javascript_to_page($js);
	}

	public function version_check_php()
	{
		// Attempt to grab the local cached file
		$cached = ee()->cache->get($this->_module."_version", \Cache::GLOBAL_SCOPE);

		// Return, since the version has already been checked.
		if($cached)
			return $cached;

		$target = parse_url("http://ping.eeharbor.com/");

		$fp = @fsockopen($target['host'], 80, $errno, $errstr, 3);

		if ( ! $fp)
			return false;

		$payload = array(
				'license' => $this->getLicenseKey(),
				'addon' => "assets",
				'version' => $this->getConfig('version'),
				'ee' => $this->getEEVersion(false),
				'domain' => ee()->config->config['base_url']
			);

		$postdata = http_build_query($payload);

		fputs($fp, "POST {$target['path']} HTTP/1.0\r\n");
		fputs($fp, "Host: {$target['host']}\r\n");
		fputs($fp, "User-Agent: Add-on Version PHP/\r\n");
		fputs($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-Length: ".strlen($postdata)."\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, "{$postdata}\r\n\r\n");

		$headers = true;
		$response = '';
		while ( ! feof($fp))
		{
			$line = fgets($fp, 4096);

			if ($headers === false)
			{
				$response .= $line;
			}
			elseif (trim($line) == '')
			{
				$headers = false;
			}
		}
		fclose($fp);

		$current_info = json_decode($response);

		// Cache version information for a day
		ee()->cache->save(
			$this->_module."_version",
			$current_info,
			60 * 60 * 24,
			\Cache::GLOBAL_SCOPE
		);

		return $current_info;
	}

	public function getLicenseKey()
	{
		return @$this->getConfig('license') ?: @$this->getConfig('license_key') ?: '';
	}

	public function module_installed($module)
	{
		return ee()->addons_model->module_installed($module);
	}
}
