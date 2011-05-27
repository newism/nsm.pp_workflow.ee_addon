<div class="mor cf">
	<div class="tg">
		<h2>Entry state</h2>
		<div class="alert info">
			(CONTENT TO BE ADDED)
		</div>
		<table class="data">
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
					<th scope="row">Pending</th>
					<td>
						(description) <?= ($data['state'] == 'pending' ? 'ACTIVE' : '') ?>
					</td>
				</tr>
				<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[state]"
						value="approved"
						<?= ($data['state'] == 'approved' ? 'checked="checked"' : '') ?>
					 /></td>
					<th scope="row">Approved</th>
					<td>
						(description) <?= ($data['state'] == 'approved' ? 'ACTIVE' : '') ?>
					</td>
				</tr>
				<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[state]"
						value="review"
						<?= ($data['state'] == 'review' ? 'checked="checked"' : '') ?>
					 /></td>
					<th scope="row">Review</th>
					<td>
						(description) <?= ($data['state'] == 'review' ? 'ACTIVE' : '') ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="tg">
		<h2>Next review date</h2>
		<div class="alert info">
			The administrator has specified that entries in this channel should be reviewed every <strong><?= $data['days_till_review'] ?> days</strong>.
			Estimated review dates will be calculated using this setting.
		</div>
		<table class="data">
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
					<th scope="row">Current review date</th>
					<td><?= $data['current_review_date_human'] ?>
						<input 
							type="hidden"
							name="<?= $input_prefix ?>[current_review_date]"
							value="<?= form_prep($data['current_review_date']) ?>"
						 />
					</td>
					<td>
						The current review date that has been set for this entry. Using this option will not alter the review date.
					</td>
				</tr>
				<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[use_date]"
						value="new_review_date"
					 /></td>
					<th scope="row">New review date</th>
					<td><input 
						type="text"
						name="<?= $input_prefix ?>[new_review_date]"
						value="<?= form_prep($data['new_review_date_human']) ?>"
					 /></td>
					<td>
						Use this option to specify a new review date.
					</td>
				</tr>
				<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[use_date]"
						value="est_next_review_date"
						<?= (!in_array('est_next_review_date', $data['allow']) ? 'disabled="disabled"' : '') ?>
					 /></td>
					<th scope="row">Estimated review date from current</th>
					<td><?= $data['est_next_review_date_human'] ?>
						<input 
							type="hidden"
							name="<?= $input_prefix ?>[est_next_review_date]"
							value="<?= form_prep($data['est_next_review_date']) ?>"
						 />
					</td>
					<td>
						Use this option if you would like the review date to be <?= $data['days_till_review'] ?> days after the currently set review date.
					</td>
				</tr>
				<tr>
					<td><input 
						type="radio"
						name="<?= $input_prefix ?>[use_date]"
						value="est_now_review_date"
						<?= (!in_array('current_review_date', $data['allow']) ? 'checked="checked"' : '') ?>
					 /></td>
					<th scope="row">Estimated review date from now</th>
					<td><?= $data['est_now_review_date_human'] ?>
						<input 
							type="hidden"
							name="<?= $input_prefix ?>[est_now_review_date]"
							value="<?= form_prep($data['est_now_review_date']) ?>"
						 />
					</td>
					<td>
						Use this option if you would like the review date to be <?= $data['days_till_review'] ?> days from now.
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>