<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require PATH_THIRD.'nsm_pp_workflow/config.php';

/**
 * NSM Publish Plus: Workflow CP 
 *
 * @package			NsmPublishPlusWorkflow
 * @version			0.0.1
 * @author			Leevi Graham <http://leevigraham.com>
 * @copyright 		Copyright (c) 2007-2010 Newism <http://newism.com.au>
 * @license 		Commercial - please see LICENSE file included with this distribution
 * @link			http://expressionengine-addons.com/nsm-example-addon
 * @see				http://expressionengine.com/public_beta/docs/development/modules.html#control_panel_file
 */

class Nsm_pp_workflow_mcp{

	public static $addon_id = NSM_PP_WORKFLOW_ADDON_ID;

	private $pages = array(
		"index"
	);

	public function __construct() {
		$this->EE =& get_instance();
	}
	
	
	public function review_entries()
	{	
		$EE =& get_instance();
		$EE->lang->loadfile('nsm_pp_workflow');
		
		if(!class_exists('Nsm_pp_workflow_model')){
			include(dirname(__FILE__).'/models/nsm_pp_workflow_model.php');
		}
		
		$entries = Nsm_pp_workflow_model::findByReviewNow();
		if(!$entries){
			// returned false, error
			die($EE->lang->line('nsm_pp_workflow_review_entries_db_select_error'));
		}elseif(count($entries) == 0){
			// returned no entries, tell user
			die($EE->lang->line('nsm_pp_workflow_review_entries_db_select_none'));
		}
		
		$entry_ids = Nsm_pp_workflow_model::getCollectionEntryIds($entries);
		
		$updates = Nsm_pp_workflow_model::updateEntryState();
		$updates = true;
		if(!$updates){
			die($EE->lang->line('nsm_pp_workflow_review_entries_db_update_error'));
		}
		
		$this->_processNotifications($entry_ids);
		
		die($EE->lang->line('nsm_pp_workflow_review_entries_ok'));
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
		$EE->db->where_in('id', $entry_ids);
		$targeted_entries = $EE->db->get();
		
		return $targeted_entries->result_array();
	}
	
	private function _processNotifications($entry_ids)
	{
		$EE =& get_instance();
		// get extension settings
		if(!class_exists('Nsm_pp_workflow_ext')){
			include(dirname(__FILE__).'/ext.nsm_pp_workflow.php');
		}
		$nsm_pp_ext = new Nsm_pp_workflow_ext();
		$settings = $nsm_pp_ext->settings;
		
		$EE->load->library('email');
		$EE->load->library('template', false, 'TMPL');
		$this->EE->TMPL->site_ids = array('1');
		
		$entries = $this->_returnEntries($entry_ids);
		foreach($entries as $entry_row){
			$entry = $entry_row;
			$channel_settings = $settings['channels'][$entry['channel_id']];
			if(!isset($channel_settings['enabled']) || $channel_settings['enabled'] == 0){
				continue;
			}
			$email_recipients = explode("\n", $channel_settings['recipients']);
			$email_from = $settings['notifications']['from'];
			$email_subject = $settings['notifications']['subject'];
			$email_message = $settings['notifications']['message'];
			
			$entry['entry_url'] = $EE->functions->create_url($entry['comment_url'].$entry['url_title']);
			$entry['cp_entry_url'] = $EE->config->item('cp_url').
										'?D=cp&C=content_publish&M=entry_form'.
										'&channel_id='.$entry['entry_id'].'&entry_id='.$entry['channel_id'];
			
			// reset template engine
			$EE->TMPL->final_template = "";
		
			// reset email object
			$EE->email->clear();
		
			$EE->email->from($email_from);
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
			$EE->email->send();
		}
	}
	
	
	
	public function index(){
		$EE =& get_instance();
		$EE->lang->loadfile('nsm_pp_workflow');
		
		$EE->load->helper('date');
		
		$vars = array('entries'=>false);
		
		if(!class_exists('Nsm_pp_workflow_model')){
			include(dirname(__FILE__).'/models/nsm_pp_workflow_model.php');
		}
		
		$entries = Nsm_pp_workflow_model::findStateReview();
		if($entries){
			$vars['entries'] = array();
			$entry_ids = Nsm_pp_workflow_model::getCollectionEntryIds($entries);
			$entries = $this->_returnEntries($entry_ids);
			foreach($entries as $entry_row){
				$entry = $entry_row;
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
		return $this->_renderLayout("index", $out);
	}

	public function _renderLayout($page, $out = FALSE) {
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line("{$page}_page_title"));
		$this->EE->cp->set_breadcrumb(self::_route(), $this->EE->lang->line('nsm_exmple_addon_module_name'));

		$nav = array();
		foreach ($this->pages as $page) {
			$nav[lang("{$page}_nav_title")] = self::_route($page);
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