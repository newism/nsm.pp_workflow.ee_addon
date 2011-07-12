<div class="mor cf">
	<?php if(!$data['channel_enabled']) : ?>
	<div class="alert info">
		<?= sprintf($EE->lang->line('nsm_pp_workflow_tab_review_not_enabled'), $extension_settings_url); ?>
	</div>
	<?php else: ?>
	<div class="tg">
		<h2><?= $EE->lang->line('nsm_pp_workflow_tab_review_state_heading') ?></h2>
		<div class="alert info">
			<?= sprintf(
					$EE->lang->line('nsm_pp_workflow_tab_review_state_intro'),
					$EE->lang->line('nsm_pp_workflow_tab_review_state_'.$data['default_state'].'_label')
				) ?>
		</div>
		<table class="data NSM_Stripeable">
			<thead>
				<tr>
					<th scope="col" style="width:18px"><?= $EE->lang->line('nsm_pp_workflow_tab_review_state_table_columns_choice') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_tab_review_state_table_columns_state') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_tab_review_state_table_columns_description') ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[state]"
						value="pending"
						<?= ($data['state'] == 'pending' ? 'checked="checked"' : '') ?>
					 /></td>
					<th scope="row"><?= $EE->lang->line('nsm_pp_workflow_tab_review_state_pending_label') ?></th>
					<td>
						<?= $EE->lang->line('nsm_pp_workflow_tab_review_state_pending_info') ?>
						<?= ($data['state'] == 'pending' ? 'ACTIVE' : '') ?>
					</td>
				</tr>
				<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[state]"
						value="approved"
						<?= ($data['state'] == 'approved' ? 'checked="checked"' : '') ?>
					 /></td>
					<th scope="row"><?= $EE->lang->line('nsm_pp_workflow_tab_review_state_approved_label') ?></th>
					<td>
						<?= $EE->lang->line('nsm_pp_workflow_tab_review_state_approved_info') ?>
						<?= ($data['state'] == 'approved' ? 'ACTIVE' : '') ?>
					</td>
				</tr>
				<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[state]"
						value="review"
						<?= ($data['state'] == 'review' ? 'checked="checked"' : '') ?>
					 /></td>
					<th scope="row"><?= $EE->lang->line('nsm_pp_workflow_tab_review_state_review_label') ?></th>
					<td>
						<?= $EE->lang->line('nsm_pp_workflow_tab_review_state_review_info') ?>
						<?= ($data['state'] == 'review' ? 'ACTIVE' : '') ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="tg">
		<h2><?= $EE->lang->line('nsm_pp_workflow_tab_review_date_heading') ?></h2>
		<div class="alert info">
			<?= sprintf($EE->lang->line('nsm_pp_workflow_tab_review_date_intro'), $data['days_till_review']) ?>
		</div>
		<table class="data NSM_Stripeable">
			<thead>
				<tr>
					<th scope="col" style="width:18px"><?= $EE->lang->line('nsm_pp_workflow_tab_review_date_table_columns_choice') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_tab_review_date_table_columns_name') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_tab_review_date_table_columns_date') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_tab_review_date_table_columns_description') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php /*<tr>
					<th scope="row">Last review date</th>
					<td><?= $data['last_review_date_human'] ?>
						<input 
							type="hidden"
							name="<?= $input_prefix ?>[last_review_date]"
							value="<?= form_prep($data['last_review_date']) ?>"
						 />
					</td>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[use_date]"
						value="last_review_date"
						<?= (!in_array('last_review_date', $data['allow']) ? 'disabled="disabled"' : '') ?>
					 /></td>
				</tr> */ ?>
				<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[use_date]"
						value="current_review_date"
						<?= (in_array('current_review_date', $data['allow']) ? 'checked="checked"' : 'disabled="disabled"') ?>
					 /></td>
					<th scope="row"><?= $EE->lang->line('nsm_pp_workflow_tab_review_date_current_label') ?></th>
					<td><?= $data['current_review_date_human'] ?>
						<input 
							type="hidden"
							name="<?= $input_prefix ?>[current_review_date]"
							value="<?= form_prep($data['current_review_date']) ?>"
						 />
					</td>
					<td>
						<?= $EE->lang->line('nsm_pp_workflow_tab_review_date_current_label') ?>
					</td>
				</tr>
				<!--<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[use_date]"
						value="est_next_review_date"
						<?= (!in_array('est_next_review_date', $data['allow']) ? 'disabled="disabled"' : '') ?>
					 /></td>
					<th scope="row"><?= $EE->lang->line('nsm_pp_workflow_tab_review_date_est_next_label') ?></th>
					<td><?= $data['est_next_review_date_human'] ?>
						<input 
							type="hidden"
							name="<?= $input_prefix ?>[est_next_review_date]"
							value="<?= form_prep($data['est_next_review_date']) ?>"
						 />
					</td>
					<td>
						<?= sprintf($EE->lang->line('nsm_pp_workflow_tab_review_date_est_next_info'), $data['days_till_review']) ?>
					</td>
				</tr>-->
				<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[use_date]"
						value="est_now_review_date"
						<?= (!in_array('current_review_date', $data['allow']) ? 'checked="checked"' : '') ?>
					 /></td>
					<th scope="row"><?= $EE->lang->line('nsm_pp_workflow_tab_review_date_est_now_label') ?></th>
					<td><?= $data['est_now_review_date_human'] ?>
						<input 
							type="hidden"
							name="<?= $input_prefix ?>[est_now_review_date]"
							value="<?= form_prep($data['est_now_review_date']) ?>"
						 />
					</td>
					<td>
						<?= sprintf($EE->lang->line('nsm_pp_workflow_tab_review_date_est_now_info'), $data['days_till_review']) ?>
					</td>
				</tr>
				<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[use_date]"
						value="new_review_date"
					 /></td>
					<th scope="row"><?= $EE->lang->line('nsm_pp_workflow_tab_review_date_new_label') ?></th>
					<td><input 
						type="text"
						name="<?= $input_prefix ?>[new_review_date]"
						value="<?= form_prep($data['new_review_date_human']) ?>"
					 /></td>
					<td>
						<?= $EE->lang->line('nsm_pp_workflow_tab_review_date_new_info') ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php endif; ?>
</div>