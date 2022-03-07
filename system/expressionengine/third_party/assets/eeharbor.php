<?php

// We can declare ee() in the global namespace here:
namespace {
	if(!function_exists('ee')) {
		function ee() {
			return get_instance();
		}
	}
}

namespace assets {

	/**
	 * EEHarbor foundation
	 *
	 * Bridges the functionality gaps between EE versions.
	 * This file namespaces, and dynamically loads the correct version of the EE helper
	 *
	 * @package			eeharbor_helper
	 * @version			1.4.3
	 * @author			Tom Jaeger <Tom@EEHarbor.com>
	 * @link			https://eeharbor.com
	 * @copyright		Copyright (c) 2016, Tom Jaeger/EEHarbor
	 */

	if(defined('APP_VER')) $app_ver = APP_VER;
	else $app_ver = ee()->config->item('app_version');

	// Pull our addon.setup.php file and define some namespaced constants because DRY.
	$addon_setup = require PATH_THIRD.'assets/addon.setup.php';

	define('assets\ADDON_AUTHOR', $addon_setup['author']);
	define('assets\ADDON_AUTHOR_URL', $addon_setup['author_url']);
	define('assets\ADDON_NAME', $addon_setup['name']);
	define('assets\ADDON_DESC', $addon_setup['description']);
	define('assets\ADDON_VER', $addon_setup['version']);

	// include the right helper, ext file, and upd file
	require_once PATH_THIRD.'assets/helpers/eeharbor_ee' . substr($app_ver, 0, 1) . '_helper.php';
	require_once PATH_THIRD.'assets/helpers/ext.eeharbor.php';
	require_once PATH_THIRD.'assets/helpers/upd.eeharbor.php';
	require_once PATH_THIRD.'assets/helpers/ft.eeharbor.php';

	class EEHarbor extends \assets\EEHelper {
		function __construct()
		{
			$params = array("module" => "assets", "module_name" => "Assets");

			parent::__construct($params);
		}
	}
}