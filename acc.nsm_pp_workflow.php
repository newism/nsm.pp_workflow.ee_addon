<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require PATH_THIRD.'nsm_publish_plus/config.php';

/**
 * NSM Publish Plus Accessory
 *
 * @package			NsmPublishPlus
 * @version			0.0.1
 * @author			Leevi Graham <http://leevigraham.com> - Technical Director, Newism
 * @copyright 		Copyright (c) 2007-2010 Newism <http://newism.com.au>
 * @license 		Commercial - please see LICENSE file included with this distribution
 * @link			http://expressionengine-addons.com/nsm-example-addon
 * @see				http://expressionengine.com/public_beta/docs/development/accessories.html
 */

class Nsm_publish_plus_acc 
{
	public $id				= NSM_PUBLISH_PLUS_ADDON_ID;
	public $version			= NSM_PUBLISH_PLUS_VERSION;
	public $name			= NSM_PUBLISH_PLUS_NAME;
	public $description		= 'Example accessory for NSM Publish Plus.';
	public $sections		= array();

	function set_sections() {
		$this->id .= "_acc";
		$this->sections['Title'] = "Content";
	}
}