<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require PATH_THIRD.'nsm_pp_workflow/config.php';

/**
 * NSM Publish Plus: Workflow Accessory
 *
 * @package			NsmPublishPlusWorkflow
 * @version			0.0.1
 * @author			Leevi Graham <http://leevigraham.com> - Technical Director, Newism
 * @copyright 		Copyright (c) 2007-2010 Newism <http://newism.com.au>
 * @license 		Commercial - please see LICENSE file included with this distribution
 * @link			http://expressionengine-addons.com/nsm-example-addon
 * @see				http://expressionengine.com/public_beta/docs/development/accessories.html
 */

class Nsm_pp_workflow_acc 
{
	public $id				= NSM_PP_WORKFLOW_ADDON_ID;
	public $version			= NSM_PP_WORKFLOW_VERSION;
	public $name			= NSM_PP_WORKFLOW_NAME;
	public $description		= 'Example accessory for NSM Publish Plus: Workflow.';
	public $sections		= array();

	function set_sections() {
		$this->id .= "_acc";
		$this->sections['Title'] = "Content";
	}
}