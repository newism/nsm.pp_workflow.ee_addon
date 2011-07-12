<?php

/**
 * NSM Publish Plus: Workflow Language File
 *
 * @package			NsmPublishPlusWorkflow
 * @version			0.9.0
 * @author			Leevi Graham <http://leevigraham.com>
 * @copyright 		Copyright (c) 2007-2010 Newism <http://newism.com.au>
 * @license 		Commercial - please see LICENSE file included with this distribution
 * @link			http://expressionengine-addons.com/nsm-example-addon
 * @see				http://expressionengine.com/public_beta/docs/development/modules.html#lang_file
 */
$lang = array(

	/* Module */
	'nsm_pp_workflow' => 'Workflow',
	'nsm_pp_workflow_module_name' => 'NSM Publish Plus: Workflow',
	'nsm_pp_workflow_module_description' => 'Extends Expression Engine\'s publishing process.',

	'nsm_pp_workflow_index_page_title' => 'Dashboard',
	'nsm_pp_workflow_index_nav_title' => 'Dashboard',
	
	'nsm_pp_workflow_find_pending_page_title' => 'Pending',
	'nsm_pp_workflow_find_pending_nav_title' => 'Pending',
	
	'nsm_pp_workflow_find_approved_page_title' => 'Approved',
	'nsm_pp_workflow_find_approved_nav_title' => 'Approved',
	
	'nsm_pp_workflow_mcp_review_entries_page_title' => 'Check Now',
	'nsm_pp_workflow_mcp_review_entries_nav_title' => 'Check Now',

	/* Extension */
	'save_extension_settings' => 'Save extension settings',

	/* Messages / Alerts */
	'alert.success.extension_settings_saved' => 'Extension settings have been saved.',
	'alert.error.no_custom_field_groups' => 'No custom field groups have been assigned to the channel',
	'alert.error.no_custom_member_fields' => 'There are no custom member fields',
	
	'nsm_pp_workflow_tab_review_date_label' => 'Workflow',
	
	'nsm_pp_workflow_tab_review_not_enabled' => 'This channel is not being monitored by this module. Please check your <a href="%1$s">extension settings</a>.',
	
	'nsm_pp_workflow_tab_review_state_heading' => 'Entry state',
	'nsm_pp_workflow_tab_review_state_intro' => 'Choose workflow state for the entry. The default state for entries in this channel is <strong>%1$s</strong>.',
	
	'nsm_pp_workflow_tab_review_state_table_columns_choice' => '',
	'nsm_pp_workflow_tab_review_state_table_columns_state' => 'State',
	'nsm_pp_workflow_tab_review_state_table_columns_description' => 'Description',
	
	
	'nsm_pp_workflow_tab_review_state_pending_label' => 'Pending',
	'nsm_pp_workflow_tab_review_state_pending_info' => 'Item is awaiting approval.',
	'nsm_pp_workflow_tab_review_state_approved_label' => 'Approved',
	'nsm_pp_workflow_tab_review_state_approved_info' => 'Item is approved for the website.',
	'nsm_pp_workflow_tab_review_state_review_label' => 'Review',
	'nsm_pp_workflow_tab_review_state_review_info' => 'The entry is deemed old and requires review for content relevancy.',
	
	'nsm_pp_workflow_tab_review_date_heading' => 'Next review date',
	'nsm_pp_workflow_tab_review_date_intro' => 'The administrator has specified that entries in this channel should be reviewed every <strong>%1$d days</strong>. '.
												'Estimated review dates will be calculated using this setting.',
	
	'nsm_pp_workflow_tab_review_date_table_columns_choice' => '',
	'nsm_pp_workflow_tab_review_date_table_columns_name' => 'Name',
	'nsm_pp_workflow_tab_review_date_table_columns_date' => 'Date',
	'nsm_pp_workflow_tab_review_date_table_columns_description' => 'Description',

												
	'nsm_pp_workflow_tab_review_date_current_label' => 'Existing review date',
	'nsm_pp_workflow_tab_review_date_current_info' => 'Using this option will not alter the review date.',
	'nsm_pp_workflow_tab_review_date_new_label' => 'New review date',
	'nsm_pp_workflow_tab_review_date_new_info' => 'Use this option to specify a new review date.',
	'nsm_pp_workflow_tab_review_date_est_next_label' => 'Estimated review date from current',
	'nsm_pp_workflow_tab_review_date_est_next_info' => 'Use this option if you would like the review date to be %1$d days after the currently set review date.',
	'nsm_pp_workflow_tab_review_date_est_now_label' => 'Estimated review date from now',
	'nsm_pp_workflow_tab_review_date_est_now_info' => 'Use this option if you would like the review date to be %1$d days from now.',
	
	
	'nsm_pp_workflow_mcp_index_review_heading' => 'Entries to be reviewed',
	'nsm_pp_workflow_mcp_index_review_intro' => 'The following table contains website entries that have been flagged for review.',
	
	'nsm_pp_workflow_mcp_index_pending_heading' => 'Entries pending approval',
	'nsm_pp_workflow_mcp_index_pending_intro' => 'The following table contains website entries that are awaiting approval.',
	
	'nsm_pp_workflow_mcp_index_approved_heading' => 'Entries approved',
	'nsm_pp_workflow_mcp_index_approved_intro' => 'The following table contains website entries that have been approved.',
	
	'nsm_pp_workflow_mcp_index_table_columns_entry_id' => 'Entry ID',
	'nsm_pp_workflow_mcp_index_table_columns_title' => 'Title',
	'nsm_pp_workflow_mcp_index_table_columns_channel' => 'Channel',
	'nsm_pp_workflow_mcp_index_table_columns_status' => 'Status',
	'nsm_pp_workflow_mcp_index_table_columns_state' => 'State',
	'nsm_pp_workflow_mcp_index_table_columns_last_edited' => 'Last edited',
	'nsm_pp_workflow_mcp_index_table_columns_review_date' => 'Review date',
	
	'nsm_pp_workflow_mcp_index_table_no_results' => 'No entries ready for review.',
	'nsm_pp_workflow_mcp_index_table_no_channels' => 'There are no channels being monitored by this module. Please check your <a href="%1$s">extension settings</a>.',
	
	
	'nsm_pp_workflow_review_entries_db_select_error' => 'A database error occured.',
	'nsm_pp_workflow_review_entries_db_select_none' => 'No entries due for review.',
	'nsm_pp_workflow_review_entries_db_update_error' => 'An error occured while updating entry states.',
	
	'nsm_pp_workflow_review_entries_email_error' => 'An error occured while sending the notification email. %1$d of %2$d emails sent.',
	'nsm_pp_workflow_review_entries_ok' => 'Notification successfully sent. %1$d of %2$d emails sent.',
	
	'nsm_pp_workflow_ext_ch_settings_heading' => 'Channel Settings',
	'nsm_pp_workflow_ext_ch_settings_columns_enable' => 'Enable?',
	'nsm_pp_workflow_ext_ch_settings_columns_channel' => 'Channel',
	'nsm_pp_workflow_ext_ch_settings_columns_days' => 'Default Review Period (days)',
	'nsm_pp_workflow_ext_ch_settings_columns_recipients' => 'Email Recipients (set multiple with new-lines)',
	'nsm_pp_workflow_ext_ch_settings_columns_status' => 'Default Status',
	
	'nsm_pp_workflow_ext_email_settings_heading' => 'Notification Settings',
	'nsm_pp_workflow_ext_email_settings_sender_name' => 'Sender Name',
	'nsm_pp_workflow_ext_email_settings_sender_address' => 'Sender Email Address',
	'nsm_pp_workflow_ext_email_settings_email_subject' => 'Email Subject',
	'nsm_pp_workflow_ext_email_settings_email_message' => 'Email Message',
	'nsm_pp_workflow_ext_email_settings_columns_status' => 'Default Status',
	
	'nsm_pp_workflow_ext_automation_heading' => 'Automation',
	'nsm_pp_workflow_ext_automation_message' => 'To trigger review updates use this URL in a cron job: <strong><a href="%1$s">%1$s</a></strong>.',
	
	'nsm_pp_workflow_channel_data_map_attr_2_sub_0_label' => 'Field',
    'nsm_pp_workflow_channel_data_map_attr_1_label' => 'Attribute 1',
    'nsm_pp_workflow_channel_data_map_attr_2_label' => 'Addresses',
    'nsm_pp_workflow_channel_data_map_attr_2_sub_1_label' => 'Street',
    'nsm_pp_workflow_channel_data_map_attr_2_sub_2_label' => 'Suburb',
    'nsm_pp_workflow_channel_data_map_attr_2_sub_3_label' => 'State',
    'nsm_pp_workflow_channel_data_map_attr_2_sub_4_label' => 'County',
    'nsm_pp_workflow_channel_data_map_attr_2_sub_5_label' => 'Zip',
    'nsm_pp_workflow_channel_data_map_attr_2_sub_6_label' => 'Lat',
    'nsm_pp_workflow_channel_data_map_attr_2_sub_7_label' => 'Long',
    'nsm_pp_workflow_channel_data_map_attr_3_label' => 'Attribute 3',

);