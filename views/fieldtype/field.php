<div class="mor cf">
	<div class="tg">
		<h2>Entry state</h2>
		<div class="alert info">
			<?= $EE->lang->line('nsm_pp_workflow_tab_review_state_intro') ?>
		</div>
		<table class="data NSM_Stripeable">
			<thead>
				<tr>
					<th scope="col" style="width:18px"></th>
					<th scope="col">State</th>
					<th scope="col">Description</th>
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
		<h2>Next review date</h2>
		<div class="alert info">
			<?= sprintf($EE->lang->line('nsm_pp_workflow_tab_review_date_intro'), $data['days_till_review']) ?>
			<!--The administrator has specified that entries in this channel should be reviewed every <strong><?= $data['days_till_review'] ?> days</strong>.
			Estimated review dates will be calculated using this setting.-->
		</div>
		<table class="data NSM_Stripeable">
			<thead>
				<tr>
					<th scope="col" style="width:18px"></th>
					<th scope="col">Name</th>
					<th scope="col">Date</th>
					<th scope="col">Description</th>
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
</div>