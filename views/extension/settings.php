<?php

/**
 * View for Control Panel Settings Form
 * This file is responsible for displaying the user-configurable settings for the NSM Multi Language extension in the ExpressionEngine control panel.
 *
 * !! All text and textarea settings must use form_prep($value); !!
 *
 * @package Nsm_pp_workflow
 * @version 1.0.0
 * @author Leevi Graham <http://leevigraham.com.au>
 * @copyright Copyright (c) 2007-2010 Newism
 * @license Commercial - please see LICENSE file included with this distribution
 **/

$EE =& get_instance();

?>

<div class="mor">
	<?= form_open(
			'C=addons_extensions&M=extension_settings&file=' . $addon_id,
			array('id' => $addon_id . '_prefs'),
			array($input_prefix."[enabled]" => TRUE)
		)
	?>

	<!-- 
	===============================
	Alert Messages
	===============================
	-->

	<?php if($error) : ?>
		<div class="alert error"><?php print($error); ?></div>
	<?php endif; ?>

	<?php if($message) : ?>
		<div class="alert success"><?php print($message); ?></div>
	<?php endif; ?>
	<?php /*
	<div class="tg">
		<h2>Enable?</h2>
		<table class="data NSM_Stripeable">
			<tbody>
				<tr>
					<th scope="row">Enable?</th>
					<td><?= $EE->nsm_pp_workflow_helper->yesNoRadioGroup($input_prefix."[enabled]", $data["enabled"]); ?></td>
				</tr>
			</tbody>
		</table>
	</div>*/ ?>

	<!-- 
	===============================
	Channel Settings / Mapping
	===============================
	-->
	
	<div class="tg">
		<h2><?= $EE->lang->line('nsm_pp_workflow_ext_ch_settings_heading') ?></h2>
		<table class="data NSM_Stripeable">
			<thead>
				<tr>
					<th scope="col" style="width:28px;"><?= $EE->lang->line('nsm_pp_workflow_ext_ch_settings_columns_enable') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_ext_ch_settings_columns_channel') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_ext_ch_settings_columns_days') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_ext_ch_settings_columns_recipients') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_ext_ch_settings_columns_status') ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($channels as $channel_id => $channel_name) : ?>
				<tr>
					<td style="text-align:center;">
						<input
							type="hidden"
							name="<?= $input_prefix."[channels][".$channel_id."][enabled]"; ?>" 
							value="0" 
						/>
						<input
							type="checkbox"
							name="<?= $input_prefix."[channels][".$channel_id."][enabled]"; ?>" 
							value="1" 
							<?= ($data['channels'][$channel_id]['enabled'] == 1 ? ' checked="checked"' : ""); ?>
						/>
					</td>
					<th scope="row"><?= $channel_name; ?></th>
					<td>
						<input
							type="text"
							name="<?= $input_prefix."[channels][".$channel_id."][next_review]"; ?>" 
							value="<?= $data['channels'][$channel_id]['next_review']; ?>"
						/>
					</td>
					<td>
						<textarea
							name="<?= $input_prefix."[channels][".$channel_id."][recipients]"; ?>" 
							rows="3"
							cols="30"
						><?= form_prep($data['channels'][$channel_id]['recipients']); ?></textarea>
					</td>
					<td>
						<select name="<?= $input_prefix."[channels][".$channel_id."][state]"; ?>">
						<?php foreach($entry_states as $state) : ?>
							<option
								value="<?= $state ?>"
								<?= ($data['channels'][$channel_id]['state'] == $state ? 'selected="selected"' : '') ?>
							>
								<?= $EE->lang->line('nsm_pp_workflow_tab_review_state_'.$state.'_label') ?>
							</option>
						<?php endforeach; ?>
						</select>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	

	<div class="tg">
		<h2><?= $EE->lang->line('nsm_pp_workflow_ext_email_settings_heading') ?></h2>
		<table class="data NSM_Stripeable">
			<tbody>
				<tr>
					<th scope="row"><?= $EE->lang->line('nsm_pp_workflow_ext_email_settings_sender_name') ?></th>
					<td><input
						type="text"
						name="<?= $input_prefix."[notifications][from_name]"; ?>" 
						value="<?= form_prep($data['notifications']['from_name']); ?>"
					/></td>
				</tr>
				<tr>
					<th scope="row"><?= $EE->lang->line('nsm_pp_workflow_ext_email_settings_sender_address') ?></th>
					<td><input
						type="text"
						name="<?= $input_prefix."[notifications][from_email]"; ?>" 
						value="<?= form_prep($data['notifications']['from_email']); ?>"
					/></td>
				</tr>
				<tr>
					<th scope="row"><?= $EE->lang->line('nsm_pp_workflow_ext_email_settings_email_subject') ?></th>
					<td><input
						type="text"
						name="<?= $input_prefix."[notifications][subject]"; ?>" 
						value="<?= form_prep($data['notifications']['subject']); ?>"
					/></td>
				</tr>
				<tr>
					<th scope="row"><?= $EE->lang->line('nsm_pp_workflow_ext_email_settings_email_message') ?></th>
					<td>
						<textarea
							name="<?= $input_prefix."[notifications][message]"; ?>" 
							rows="3"
							cols="30"
						><?= form_prep($data['notifications']['message']); ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="tg">
		<h2><?= $EE->lang->line('nsm_pp_workflow_ext_automation_heading') ?></h2>
		<div class="alert info">
		<?= sprintf(
				$EE->lang->line('nsm_pp_workflow_ext_automation_message'),
				$EE->config->item('site_url').'?ACT='.$EE->cp->fetch_action_id('Nsm_pp_workflow_mcp', 'review_entries')
			) ?>
		</div>
	</div>


	<!-- 
	===============================
	Submit Button
	===============================
	-->

	<div class="actions">
		<input type="submit" class="submit" value="<?php print lang('save_extension_settings') ?>" />
	</div>

	<?= form_close(); ?>
</div>