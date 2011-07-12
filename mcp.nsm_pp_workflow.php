<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require PATH_THIRD.'nsm_pp_workflow/config.php';

/**
 * NSM Publish Plus: Workflow CP 
 *
 * @package			NsmPublishPlusWorkflow
 * @version			0.9.0
 * @author			Leevi Graham <http://leevigraham.com>
 * @copyright 		Copyright (c) 2007-2010 Newism <http://newism.com.au>
 * @license 		Commercial - please see LICENSE file included with this distribution
 * @link			http://expressionengine-addons.com/nsm-example-addon
 * @see				http://expressionengine.com/public_beta/docs/development/modules.html#control_panel_file
 */

class Nsm_pp_workflow_mcp{

	public static $addon_id = NSM_PP_WORKFLOW_ADDON_ID;

	private $pages = array(
		"index",
		"find_pending",
		"find_approved",
		"mcp_review_entries"
	);

	public function __construct() {
		$this->EE =& get_instance();
		
		// get extension settings
		if(!class_exists('Nsm_pp_workflow_ext')){
			include(dirname(__FILE__).'/ext.nsm_pp_workflow.php');
		}
		$nsm_pp_ext = new Nsm_pp_workflow_ext();
		$this->settings = $nsm_pp_ext->settings;
	}

	public function mcp_review_entries()
	{
		$review_status = $this->review_entries();
		$this->EE->session->set_flashdata('message_'.$review_status[0], $review_status[1]);
		$this->EE->functions->redirect( self::_route('index') );
	}

	public function cron_review_entries(){
		$review_status = $this->review_entries();
		die( $review_status[1] );
	}

	public function review_entries()
	{	
		$EE =& get_instance();
		$EE->lang->loadfile('nsm_pp_workflow');
		
		if(!class_exists('Nsm_pp_workflow_model')){
			include(dirname(__FILE__).'/models/nsm_pp_workflow_model.php');
		}
		
		if(!$this->settings){
			// get extension settings
			if(!class_exists('Nsm_pp_workflow_ext')){
				include(dirname(__FILE__).'/ext.nsm_pp_workflow.php');
			}
			$nsm_pp_ext = new Nsm_pp_workflow_ext();
			$this->settings = $nsm_pp_ext->settings;
		}
		
		$channel_ids = $this->_returnChannelIDs($this->settings);
		if(!count($channel_ids)){
			// no channels set
			return array('error', $EE->lang->line('nsm_pp_workflow_review_entries_no_channels'));
		}
		
		$entries = Nsm_pp_workflow_model::findByReviewNow($channel_ids);
		if(count($entries) == 0){
			// returned no entries, tell user
			return array('success', $EE->lang->line('nsm_pp_workflow_review_entries_db_select_none'));
		}elseif(!$entries){
			// returned false, error
			return array('error', $EE->lang->line('nsm_pp_workflow_review_entries_db_select_error'));
		}
		
		$entry_ids = Nsm_pp_workflow_model::getCollectionEntryIds($entries);
		
		$updates = Nsm_pp_workflow_model::updateEntryState($channel_ids);
		$updates = true;
		if(!$updates){
			return array('error', $EE->lang->line('nsm_pp_workflow_review_entries_db_update_error'));
		}
		
		$notification_status = $this->_processNotifications($entry_ids);
		if($notification_status['sent'] == $notification_status['pending']){
			$message = sprintf(
							$EE->lang->line('nsm_pp_workflow_review_entries_ok'), 
							$notification_status['sent'],
							$notification_status['pending']
						);
			return array('success', $message);
		}else{
			$message = sprintf(
							$EE->lang->line('nsm_pp_workflow_review_entries_email_error'), 
							$notification_status['sent'],
							$notification_status['pending']
						);
			return array('success', $message);
		}
	}
	
	private function _returnChannelIDs($settings)
	{
		$channel_ids = array();
		foreach($settings['channels'] as $channel_id => $channel_settings){
			if($channel_settings['enabled'] == 1){
				$channel_ids[$channel_id] = 1;
			}
		}
		$channel_ids = array_keys($channel_ids);
		return $channel_ids;
	}
	
	private function _returnEntries($entry_ids)
	{
		$entries = array();
		
		$EE =& get_instance();
		$table_name = Nsm_pp_workflow_model::getTableName();
		// get the entries
		$EE->db->from($table_name);
		$EE->db->join('channel_titles', 'channel_titles.entry_id = '.$table_name.'.entry_id', 'left');
		$EE->db->join('channels', 'channel_titles.channel_id = channels.channel_id', 'left');
		$EE->db->where_in('channel_titles.entry_id', $entry_ids);
		$targeted_entries = $EE->db->get();
		return $targeted_entries->result_array();
	}
	
	private function _processNotifications($entry_ids)
	{
		$emails_pending = 0;
		$emails_sent = 0;
		
		$EE =& get_instance();
		
		$EE->load->library('email');
		$EE->load->library('template', false, 'TMPL');
		$this->EE->TMPL->site_ids = array('1');
		
		$entries = $this->_returnEntries($entry_ids);
		foreach($entries as $entry_row){
			$entry = $entry_row;
			$channel_settings = $this->settings['channels'][$entry['channel_id']];
			if(!isset($channel_settings['enabled']) || $channel_settings['enabled'] == 0){
				continue;
			}
			$email_recipients = explode("\n", $channel_settings['recipients']);
			$email_from_name = $this->settings['notifications']['from_name'];
			$email_from_address = $this->settings['notifications']['from_email'];
			$email_subject = $this->settings['notifications']['subject'];
			$email_message = $this->settings['notifications']['message'];
			
			$entry['entry_url'] = $EE->functions->create_url($entry['comment_url'].$entry['url_title']);
			$entry['cp_entry_url'] = $EE->config->item('cp_url').
										'?D=cp&C=content_publish&M=entry_form'.
										'&channel_id='.$entry['entry_id'].'&entry_id='.$entry['channel_id'];
			
			$emails_pending += count($email_recipients);
			
			// reset template engine
			$EE->TMPL->final_template = "";
		
			// reset email object
			$EE->email->clear();
		
			$EE->email->from($email_from_address, $email_from_name);
			$EE->email->to($email_recipients);
			
			// set the e mail subject and parse variables
			$email_subject = $EE->TMPL->parse_variables_row($email_subject, $entry);
			$EE->email->subject($email_subject);

			// set the email message body and parse variables
			//$tagdata = $EE->TMPL->fetch_template($template['group'], $template['name'], FALSE);
			$tagdata = $EE->TMPL->remove_ee_comments($email_message);
			$tagdata = $EE->TMPL->convert_xml_declaration($email_message);
			$tagdata = $EE->TMPL->parse_variables_row($tagdata, $entry);
			$tagdata = $EE->functions->prep_conditionals($tagdata, $entry);
			$EE->TMPL->parse($tagdata);
			$email_message = $EE->TMPL->parse_globals($EE->TMPL->final_template);
			$EE->email->message($email_message);
			
			// send the email
			if($EE->email->send()){
				$emails_sent += count($email_recipients);
			}
		}
		return array(
					'pending' => $emails_pending,
					'sent' => $emails_sent
				);
	}
	
	
	
	public function index(){
		return $this->dashboard('review', 'index');
	}
	
	public function find_pending(){
		return $this->dashboard('pending', 'find_pending');
	}
	
	public function find_approved(){
		return $this->dashboard('approved', 'find_approved');
	}
	
	
	public function dashboard($find_state = 'review', $page = 'index'){
		$EE =& get_instance();
		$EE->lang->loadfile('nsm_pp_workflow');
		$EE->load->helper('date');
		
		if(!$this->settings){
			// get extension settings
			if(!class_exists('Nsm_pp_workflow_ext')){
				include(dirname(__FILE__).'/ext.nsm_pp_workflow.php');
			}
			$nsm_pp_ext = new Nsm_pp_workflow_ext();
			$this->settings = $nsm_pp_ext->settings;
		}

		$channel_ids = $this->_returnChannelIDs($this->settings);

		$vars = array(
		    'EE' => $EE, 
		    'entries' => false, 
		    'error_tag' => 'no_results', 
		    'filter_state' => $find_state,
		    'extension_settings_url' => BASE.AMP.'C=addons_extensions'.AMP.'M=extension_settings'.AMP.'file=nsm_pp_workflow'
		);
		
		if(!class_exists('Nsm_pp_workflow_model')){
			include(dirname(__FILE__).'/models/nsm_pp_workflow_model.php');
		}
		
		$entries = false;

		if($channel_ids){
			$entries = Nsm_pp_workflow_model::findByState($find_state, $channel_ids);
		}else{
			$vars['error_tag'] = 'no_channels';
		}

		if($entries){
			$vars['entries'] = array();
			$entry_ids = Nsm_pp_workflow_model::getCollectionEntryIds($entries);
			$entries = $this->_returnEntries($entry_ids);
			foreach($entries as $entry_row){
				$entry = $entry_row;
				unset($entry->EE);
				$entry['edit_date'] = strtotime($entry['edit_date']);
				$entry['days_in_review'] = ceil(($entry['last_review_date'] - mktime()) / (24 * 60 * 60));
				if($entry['days_in_review'] < 1){ $entry['days_in_review'] = "None"; }
				$entry['days_since_edit'] = ceil(($entry['last_review_date'] - $entry['edit_date']) / (24 * 60 * 60));
				if($entry['days_since_edit'] < 1){ $entry['days_since_edit'] = "None"; }
				$entry['cp_entry_url'] = BASE.AMP.'C=content_publish'.AMP.'M=entry_form'.AMP.'channel_id='.$entry['channel_id'].AMP.'entry_id='.$entry['entry_id'];
				$vars['entries'][] = $entry;
			}
		}
		$out = $this->EE->load->view("module/index", $vars, TRUE);
		return $this->_renderLayout($page, $out);
	}
	

	public function _renderLayout($page, $out = FALSE) {
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line(self::$addon_id."_{$page}_page_title"));
		$this->EE->cp->set_breadcrumb(self::_route(), $this->EE->lang->line('nsm_pp_workflow_module_name'));

		$nav = array();
		foreach ($this->pages as $page) {
			$nav[lang(self::$addon_id."_{$page}_nav_title")] = self::_route($page);
		}
		$this->EE->cp->set_right_nav($nav);
		return "<div class='mor'>{$out}</div>";
	}

	/**
	 * Build a CP route URL based on params and method
	 *
	 * @access public
	 * @param $method string The method called in the CP
	 * @param $params array Key value pair that will be turned into query params
	 * @param $add_base boolean Add the CP base URL. This can include a session string / subfolders
	 * @retutn string The route URL
	 */
	public static function _route($method = 'index', $params = array(), $add_base = true) {
		$base = ($add_base) ? BASE . AMP : '';
		$params = array_merge(array(
			'C' =>  'addons_modules',
			'M' => 'show_module_cp',
			'module' => NSM_PP_WORKFLOW_ADDON_ID,
			'method' => $method
		), $params);
		return $base . http_build_query($params);
	}
	
}