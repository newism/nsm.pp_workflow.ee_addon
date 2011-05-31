<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require PATH_THIRD.'nsm_pp_workflow/config.php';

/**
 * NSM Publish Plus: Workflow Fieldtype
 *
 * @package			NsmPublishPlusWorkflow
 * @version			0.0.1
 * @author			Leevi Graham <http://leevigraham.com>
 * @copyright 		Copyright (c) 2007-2010 Newism <http://newism.com.au>
 * @license 		Commercial - please see LICENSE file included with this distribution
 * @link			http://expressionengine-addons.com/nsm-example-addon
 * @see				http://expressionengine.com/public_beta/docs/development/fieldtypes.html
 */

class Nsm_pp_workflow_ft extends EE_Fieldtype
{
	/**
	 * Field info - Required
	 * 
	 * @access public
	 * @var array
	 */
	public $info = array(
		'version'		=> NSM_PP_WORKFLOW_VERSION,
		'name'			=> NSM_PP_WORKFLOW_NAME
	);

	public $addon_id		= NSM_PP_WORKFLOW_ADDON_ID;

	/**
	 * The fieldtype global settings array
	 * 
	 * @access public
	 * @var array
	 */
	public $settings = array();

	/**
	 * The field type - used for form field prefixes. Must be unique and match the class name. Set in the constructor
	 * 
	 * @access private
	 * @var string
	 */
	public $field_type = '';

	/**
	 * Constructor
	 * 
	 * @access public
	 */
	public function __construct() {
		parent::EE_Fieldtype();
	}


	//----------------------------------------
	// DISPLAY FIELD / CELL / VARIABLE
	//----------------------------------------

	/**
	 * Takes db / post data and parses it so we have the same info to work with every time
	 *
	 * @access private 
	 * @param $data mixed The data we need to prep
	 * @return array The new array of data
	 */
	private function _prepData($data) {

		if ( ! function_exists('json_decode')) {
			$$EE->load->library('Services_json');
		}

		$default_data = array(
			'value_1' => false,
			'value_2' => false,
			'value_3' => false
		);

		if(empty($data)) {
			$data = array();
		} elseif(is_string($data)) {
			$data = json_decode($data, true);
		}
		return $data;
		//return $this->_mergeRecursive($default_data, $data);
	}
	
	/**
	 * Display the field in the publish form
	 * 
	 * @access public
	 * @param $data String Contains the current field data. Blank for new entries.
	 * @param $input_name String the input name prefix
	 * @param $field_id String The field id - Low variables
	 * @return String The custom field HTML
	 */
	public function display_field($data, $input_name = false, $field_id = false) {

		if(!$field_id) {
			$field_id = $this->field_name;
		}

		if(!$input_name) {
			$input_name = $this->field_name;
		}

		$this->_loadResources();

		$vars = array(
			'EE' => $this->EE,
			'data' => $data,
			'title' => 'Publish Plus: Workflow',
			'input_prefix' => $input_name
		);

		// Use the native CI Loader class
		// We need to to do this becuase this field may have been loaded by Matrix or Low varibales
		return $this->EE->load->_ci_load(array(
			'_ci_vars' => $vars,
			'_ci_path' => PATH_THIRD . 'nsm_pp_workflow/views/fieldtype/field.php',
			'_ci_return' => true
		));
	}

	/**
	 * Publish form validation
	 * 
	 * @access public
	 * @param $data array Contains the submitted field data.
	 * @return mixed TRUE or an error message
	 */
	public function validate($data) {
		return TRUE;
	}




	/**
	 * Get the current themes URL from the theme folder + / + the addon id
	 * 
	 * @access private
	 * @return string The theme URL
	 */
	private function _getThemeUrl() {
		$EE =& get_instance();
		if(!isset($EE->session->cache[$this->addon_id]['theme_url'])) {
			$theme_url = $EE->config->item('theme_folder_url');
			if (substr($theme_url, -1) != '/') {
				$theme_url .= '/';
			}
			$theme_url .= "third_party/" . $this->addon_id;
			$EE->session->cache[$this->addon_id]['theme_url'] = $theme_url;
		}
		return $EE->session->cache[$this->addon_id]['theme_url'];
	}
	
	/**
	 * Load CSS and JS resources for the fieldtype
	 */
	private function _loadResources() {
		if(!isset($this->EE->cache[__CLASS__]['resources_loaded'])) {
			$theme_url = $this->_getThemeUrl();
			$this->EE->cp->add_to_head("<link rel='stylesheet' href='{$theme_url}/styles/admin.css' type='text/css' media='screen' charset='utf-8' />");
			$this->EE->cp->add_to_foot("<script src='{$theme_url}/scripts/admin.js' type='text/javascript' charset='utf-8'></script>");
			$this->EE->cache[__CLASS__]['resources_loaded'] = true;
		}
	}


}
//END CLASS