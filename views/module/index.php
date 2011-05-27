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
		<h2>Entries to be reviewed</h2>
		<div class="alert info">
			(CONTENT TO BE ADDED)
		</div>
		<table class="data">
			<thead>
				<tr>
					<th scope="col">Entry ID</th>
					<th scope="col">Title</th>
					<th scope="col">Channel</th>
					<th scope="col">Status</th>
					<th scope="col">State</th>
					<th scope="col">Last edited</th>
					<th scope="col">Review date</th>
					<!--<th scope="col">Days unaltered</th>
					<th scope="col">Days in review</th>-->
				</tr>
			</thead>
			<tbody>
		<?php if(!$entries): ?>
			<tr>
				<td colspan="7" class="alert">No entries ready for review.</td>
			</tr>
		<?php else: ?>
			<?php foreach($entries as $entry) : ?>
				<tr>
					<th scope="row"><?= $entry['entry_id'] ?></th>
					<td>
						<a href="<?= $entry['cp_entry_url'] ?>">
							<?= $entry['title'] ?>
						</a>
					</td>
					<td><?= $entry['channel_title'] ?></td>
					<td><?= $entry['status'] ?></td>
					<td><?= $entry['entry_state'] ?></td>
					<td><?= unix_to_human($entry['edit_date']) ?></td>
					<td><?= unix_to_human($entry['next_review_date']) ?></td>
					<!--<td><?= $entry['days_since_edit'] ?></td>
					<td><?= $entry['days_in_review'] ?></td>-->
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
