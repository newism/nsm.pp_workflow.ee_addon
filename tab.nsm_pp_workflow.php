<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require PATH_THIRD.'nsm_pp_workflow/config.php';

/**
 * NSM Publish Plus: Workflow Tab
 *
 * @package			NsmPublishPlusWorkflow
 * @version			0.10.2
 * @author			Leevi Graham <http://leevigraham.com>
 * @copyright 		Copyright (c) 2007-2010 Newism <http://newism.com.au>
 * @license 		Commercial - please see LICENSE file included with this distribution
 * @link			http://expressionengine-addons.com/nsm-example-addon
 * @see				http://expressionengine.com/public_beta/docs/development/modules.html#tab_file
 */

class Nsm_pp_workflow_tab
{
	/**
	 * This function creates the fields that will be displayed on the publish page. It must return $settings, a multidimensional associative array specifying the display settings and values associated with each of your custom fields.
	 *
	 * The settings array:
	 * field_id: The name of the field
	 * field_type: The field type
	 * field_label: The field label- typically a language variable is used here
	 * field_instructions: Instructions for the field
	 * field_required: Indicates whether to include the 'required' class next to the field label: y/n
	 * field_data: The current data, if applicable
	 * field_list_items: An array of options, otherwise an empty string.
	 * options: An array of options, otherwise an empty string.
	 * selected: The selected value if applicable to the field_type
	 * field_fmt: Allowed field format options, if applicable: an associative array or empty string.
	 * field_show_fmt: Determines whether the field format dropdown shows: y/n. Note- if 'y', you must specify the options available in field_fmt
	 * field_pre_populate: Allows you to pre-populate a field when it is a new entry.
	 * field_text_direction: The direction of the text: ltr/rtl
	 *
	 * @param int $channel_id The channel_id the entry is currently being created in
	 * @param mixed $entry_id The entry_id if an edit, false for new entries
	 * @return array The settings array
	 */
	public function publish_tabs($channel_id, $entry_id = FALSE) {
		// Uncomment to hide tab.
		// if(!class_exists('Nsm_pp_workflow_ext'))
		// 	require(PATH_THIRD . "nsm_pp_workflow/ext.nsm_pp_workflow.php");
		// 
		// $ext = new Nsm_pp_workflow_ext();
		// if(
		// 	! isset($ext->settings['channels'][$channel_id])
		// 	|| empty($ext->settings['channels'][$channel_id]['show_tab'])
		// )
		// 	return array();
		
		$EE =& get_instance();
		$EE->lang->loadfile('nsm_pp_workflow');
		
		if(!class_exists('Nsm_pp_workflow_ext')){
			include(dirname(__FILE__).'/ext.nsm_pp_workflow.php');
		}
		$nsm_pp_ext = new Nsm_pp_workflow_ext();
		$settings = $nsm_pp_ext->settings;
		
		// is this channel being managed by pp_workflow?
		if(!isset($settings['channels'][$channel_id]) || $settings['channels'][$channel_id]['enabled'] == 0){
			$field_settings[] = array(
				'field_id' => 'nsm_pp_workflow', // This must match a key in Nsm_pp_workflow_upd::tabs()
				'field_type' => 'nsm_pp_workflow',
				'field_label' => $EE->lang->line('nsm_pp_workflow_tab_review_date_label'),
				'field_instructions' => '',
				'field_required' => '',
				'field_data' => array('channel_enabled' => false),
				'field_list_items' => '',
				'options' => '',
				'selected' => '',
				'field_fmt' => '',
				'field_show_fmt' => 'n',
				'field_pre_populate' => '',
				'field_text_direction' => 'ltr',
				'field_channel_id' => $channel_id
			);
			return $field_settings;
		}
		
		if(isset($settings['channels'][$channel_id]['next_review'])){
			$days_till_next_review = intval($settings['channels'][$channel_id]['next_review']);
		}else{
			$days_till_next_review = 60;
		}
		
		if(isset($settings['channels'][$channel_id]['state'])){
			$default_state = $settings['channels'][$channel_id]['state'];
		}else{
			$default_state = 'pending';
		}
		
		$EE->load->helper('date');
		
		if(!class_exists('Nsm_pp_workflow_model')){
			include(dirname(__FILE__).'/models/nsm_pp_workflow_model.php');
		}
		
		// defaults
		$default_data = array(
			'channel_enabled' => true,
			'state' => $default_state,
			'default_state' => $default_state,
			'days_till_review' => $days_till_next_review,
			'allow' => array('est_now_review_date'),
			'last_review_date' => 0,
			'last_review_date_human' => "(No date recorded)",
			'current_review_date' => 0,
			'current_review_date_human' => "(No date set)",
			'new_review_date' => 0,
			'new_review_date_human' => "(No date set)",
			'est_next_review_date' => 0,
			'est_next_review_date_human' => "(Estimate unavailable)",
			'est_now_review_date' => 0,
			'est_now_review_date_human' => ''
		);
		
		$nsm_pp_model = Nsm_pp_workflow_model::findByEntryId($entry_id);
		if(!$nsm_pp_model){
			$est_now_review_date = now() + ((24 * 60 * 60) * $days_till_next_review);
			$est_now_review_date_human = unix_to_human($est_now_review_date);
			
			$new_review_date = $est_now_review_date;
			$new_review_date_human = $est_now_review_date_human;
			
			$data = array(
				'new_review_date' => $new_review_date,
				'new_review_date_human' => $new_review_date_human,
				'est_now_review_date' => $est_now_review_date,
				'est_now_review_date_human' => $est_now_review_date_human
			);
			
		}else{
			$last_review_date = $nsm_pp_model->last_review_date;
			$last_review_date_human = unix_to_human($last_review_date);
			
			$current_review_date = $nsm_pp_model->next_review_date;
			$current_review_date_human = unix_to_human($current_review_date);
			
			$est_next_review_date = $current_review_date + ((24 * 60 * 60) * $days_till_next_review);
			$est_next_review_date_human = unix_to_human($est_next_review_date);
			
			$est_now_review_date = now() + ((24 * 60 * 60) * $days_till_next_review);
			$est_now_review_date_human = unix_to_human($est_now_review_date);
			
			$data = array(
				'state' => $nsm_pp_model->entry_state,
				'allow' => array(
								'current_review_date',
								'est_next_review_date',
								'est_now_review_date'
							),
				'current_review_date' => $current_review_date,
				'current_review_date_human' => $current_review_date_human,
				'new_review_date' => $current_review_date,
				'new_review_date_human' => $current_review_date_human,
				'est_next_review_date' => $est_next_review_date,
				'est_next_review_date_human' => $est_next_review_date_human,
				'est_now_review_date' => $est_now_review_date,
				'est_now_review_date_human' => $est_now_review_date_human
			);
			
			if($last_review_date > 0){
				$data['allow'][] = 'last_review_date';
				$data['last_review_date'] = $last_review_date;
				$data['last_review_date_human'] = $last_review_date_human;
			}
			
		}
		
		$data = array_merge($default_data, $data);
		
		$field_settings[] = array(
			'field_id' => 'nsm_pp_workflow', // This must match a key in Nsm_pp_workflow_upd::tabs()
			'field_type' => 'nsm_pp_workflow',
			'field_label' => $EE->lang->line('nsm_pp_workflow_tab_review_date_label'),
			'field_instructions' => '',
			'field_required' => '',
			'field_data' => $data,
			'field_list_items' => '',
			'options' => '',
			'selected' => '',
			'field_fmt' => '',
			'field_show_fmt' => 'n',
			'field_pre_populate' => '',
			'field_text_direction' => 'ltr',
			'field_channel_id' => $channel_id
		);
		return $field_settings;
	}

	/**
	 * Allows you to validate the data after the publish form has been submitted but before any additions to the database. Returns FALSE if there are no errors, an array of errors otherwise.
	 *
	 * @param $params  multidimensional associative array containing all of the data available on the current submission.
	 * @return mixed Returns FALSE if there are no errors, an array of errors otherwise
	 */
	public function validate_publish($params) {
		$errors = FALSE;
		return $errors;
	}

	/**
	 * Allows the insertion of data after the core insert/update has been done, thus making available the current $entry_id. Returns nothing.
	 *
	 * @param array $params an associative array, the top level arrays consisting of: 'meta', 'data', 'mod_data', and 'entry_id'.
	 * @return void
	 */
	public function publish_data_db($params) {
		$EE =& get_instance();
		$EE->load->helper('date');
		
		$channel_id = $params['meta']['channel_id'];
		$entry_id = $params['entry_id'];
		
		if(!class_exists('Nsm_pp_workflow_model')){
			include(dirname(__FILE__).'/models/nsm_pp_workflow_model.php');
		}
		
		if(!class_exists('Nsm_pp_workflow_ext')){
			include(dirname(__FILE__).'/ext.nsm_pp_workflow.php');
		}
		$nsm_pp_ext = new Nsm_pp_workflow_ext();
		$settings = $nsm_pp_ext->settings;
		// is this channel being managed by pp_workflow?
		if( empty($settings['channels'][$channel_id]['enabled']) ){
			return true;
		}
		
		$default_data = array(
			'state' => $settings['channels'][$channel_id]['state'],
			'use_date' => 'est_next_review_date',
			'est_next_review_date' => now() + ((60*60*24) * $settings['channels'][$channel_id]['next_review'])
		);
		
		$data = array_merge($default_data, $params['mod_data']['nsm_pp_workflow']);
		
		$nsm_pp_model = Nsm_pp_workflow_model::findByEntryId($entry_id);
		// no existing entry? make one.
		if(!$nsm_pp_model){
			$model_data = array(
				'entry_id' => $entry_id,
				'channel_id' => $channel_id,
				'entry_state' => $data['state'],
				'last_review_date' => 0,
				'next_review_date' => 0,
				'site_id' => $EE->config->item('site_id')
			);
			$nsm_pp_model = new Nsm_pp_workflow_model($model_data);
			$nsm_pp_model->add();
		}
		$new_review_date = $data[ $data['use_date'] ];
		
		if($data['use_date'] == 'new_review_date'){
			$new_review_date = human_to_unix($new_review_date);
		}
		
		$nsm_pp_model->channel_id = $channel_id;
		$nsm_pp_model->entry_state = $data['state'];
		
		if($data['use_date'] !== 'current_review_date'){
			$nsm_pp_model->setNewReviewDate($new_review_date);
		}
		
		if($nsm_pp_model->update()){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Called near the end of the entry delete function, this allows you to sync your records if any are tied to channel entry_ids. Returns nothing.
	 *
	 * @param array $entry_ids The deleted entries
	 * @return void
	 */
	public function publish_data_delete_db($entry_ids) {
		if(!class_exists('Nsm_pp_workflow_model')){
			include(dirname(__FILE__).'/models/nsm_pp_workflow_model.php');
		}
		foreach($entry_ids['entry_ids'] as $entry_id){
			$nsm_pp_model = Nsm_pp_workflow_model::findByEntryId($entry_id);
			if($nsm_pp_model !== false){
				$nsm_pp_model->delete();
			}
		}
	}
}