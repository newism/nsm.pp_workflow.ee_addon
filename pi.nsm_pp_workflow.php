<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require PATH_THIRD.'nsm_publish_plus/config.php';

/**
 * NSM Publish Plus Plugin
 * 
 * Generally a module is better to use than a plugin if if it has not CP backend
 *
 * @package			NsmPublishPlus
 * @version			0.0.1
 * @author			Leevi Graham <http://leevigraham.com>
 * @copyright 		Copyright (c) 2007-2010 Newism <http://newism.com.au>
 * @license 		Commercial - please see LICENSE file included with this distribution
 * @link			http://expressionengine-addons.com/nsm-example-addon
 * @see 			http://expressionengine.com/public_beta/docs/development/plugins.html
 */

/**
 * Plugin Info
 *
 * @var array
 */
$plugin_info = array(
	'pi_name' => NSM_PUBLISH_PLUS_NAME,
	'pi_version' => NSM_PUBLISH_PLUS_VERSION,
	'pi_author' => 'Leevi Graham',
	'pi_author_url' => 'http://leevigraham.com/',
	'pi_description' => 'Plugin description',
	'pi_usage' => "Refer to the included README"
);

class Nsm_publish_plus{

	/**
	 * The return string
	 *
	 * @var string
	 */
	var $return_data = "";

	function Nsm_publish_plus() {
		$EE =& get_instance();
		$this->return_data = "NSM Publish Plus Output";
	}

}