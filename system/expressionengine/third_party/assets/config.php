<?php

if (! defined('ASSETS_NAME'))
{
	define('ASSETS_NAME', 'Assets');
	define('ASSETS_VER',  '3.1.0');
	define('ASSETS_DESC', 'Heavy duty asset management');
	define('ASSETS_DOCS', 'http://eeharbor.com/assets/documentation');
}

// NSM Addon Updater
$config['name'] = ASSETS_NAME;
$config['version'] = ASSETS_VER;
$config['nsm_addon_updater']['versions_xml'] = 'http://eeharbor.com/assets/releasenotes.rss';
