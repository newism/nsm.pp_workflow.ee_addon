<?php

/**
 * Config file for NSM Publish Plus
 *
 * @package			NsmPublishPlus
 * @version			0.0.1
 * @author			Leevi Graham <http://leevigraham.com>
 * @copyright 		Copyright (c) 2007-2010 Newism <http://newism.com.au>
 * @license 		Commercial - please see LICENSE file included with this distribution
 * @link			http://expressionengine-addons.com/nsm-example-addon
 */

if(!defined('NSM_PUBLISH_PLUS_VERSION')) {
	define('NSM_PUBLISH_PLUS_VERSION', '0.0.1');
	define('NSM_PUBLISH_PLUS_NAME', 'NSM Publish Plus');
	define('NSM_PUBLISH_PLUS_ADDON_ID', 'nsm_publish_plus');
}

$config['name'] 	= NSM_PUBLISH_PLUS_NAME;
$config["version"] 	= NSM_PUBLISH_PLUS_VERSION;

$config['nsm_addon_updater']['versions_xml'] = 'http://github.com/newism/nsm.publish_plus.ee_addon/raw/master/versions.xml';
