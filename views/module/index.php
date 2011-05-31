<?php
	/**
	 * Forms open and close tags should always be in the view
	 * which allows for multiple forms per view
	 *
	 */
/*form_open(
		Nsm_pp_workflow_mcp::_route(
			'index',
			array('param' => 'some_value'),
			false
		), // Form submission URL
		array('form_param' => 'form_param_value'), // Form attributes
		array('hidden_field' => 'hidden_field_value') // Form hidden fields
	)

	<div class="actions">
		<button type="submit" class="submit">Submit</button>
	</div>
 form_close(); 
*/

?>

<div class="mor">
	<div class="tg">
		<h2><?= $EE->lang->line('nsm_pp_workflow_mcp_index_review_heading') ?></h2>
		<div class="alert info">
			<?= $EE->lang->line('nsm_pp_workflow_mcp_index_review_intro') ?>
		</div>
		<table class="data col-sortable NSM_Stripeable">
			<col class="id" style="width:90px;"/>
			<thead>
				<tr>
					<th scope="col" class="id"><?= $EE->lang->line('nsm_pp_workflow_mcp_index_review_table_columns_entry_id') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_mcp_index_review_table_columns_title') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_mcp_index_review_table_columns_channel') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_mcp_index_review_table_columns_status') ?></th>
					<th scope="col"><?= $EE->lang->line('nsm_pp_workflow_mcp_index_review_table_columns_state') ?></th>
					<th scope="col" class="date"><?= $EE->lang->line('nsm_pp_workflow_mcp_index_review_table_columns_last_edited') ?></th>
					<th scope="col" class="date"><?= $EE->lang->line('nsm_pp_workflow_mcp_index_review_table_columns_review_date') ?></th>
				</tr>
			</thead>
			<tbody>
		<?php if(!$entries): ?>
			<tr>
				<td colspan="7" class="alert">
					<?= $EE->lang->line('nsm_pp_workflow_mcp_index_review_table_no_results') ?>
				</td>
			</tr>
		<?php else: ?>
			<?php foreach($entries as $entry) : ?>
				<tr>
					<td><?= $entry['entry_id'] ?></td>
					<th scope="row">
						<a href="<?= $entry['cp_entry_url'] ?>">
							<?= $entry['title'] ?>
						</a>
					</th>
					<td><?= $entry['channel_title'] ?></td>
					<td><?= $entry['status'] ?></td>
					<td><?= $entry['entry_state'] ?></td>
					<td><?= unix_to_human($entry['edit_date']) ?></td>
					<td><?= unix_to_human($entry['next_review_date']) ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
