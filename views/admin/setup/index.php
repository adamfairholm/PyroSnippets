<section class="title">
	<h4><?php echo lang('snippets.name'); ?></h4>
</section>

<section class="item">

	<div class="content">

	<?php if (!empty($snippets)): ?>

		<table border="0" class="table-list" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo lang('snippets.snippet_name'); ?></th>
					<th><?php echo lang('snippets.snippet_type'); ?></th>
					<th><?php echo lang('snippets.syntax'); ?></th>
					<th></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="6">
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($snippets as $snippet): ?>
					<tr>
						<td><?php echo $snippet->name; ?></td>
						<td><?php echo $snippet_types[$snippet->type]; ?></td>
						<td>{{ snippet:<?php echo $snippet->slug; ?> }}</td>
						<td class="actions">
							<a href="<?php echo site_url('admin/snippets/setup/edit_snippet/'.$snippet->id);?>" class="button edit"><?php echo lang('global:edit'); ?></a>
							<?php if(group_has_role('snippets', 'admin_snippets')): ?><a href="<?php echo site_url('admin/snippets/setup/delete_snippet/'.$snippet->id);?>" class="confirm button delete"><?php echo lang('global:delete'); ?></a><?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	<?php else: ?>
		<div class="no_data"><?php echo lang('snippets.no_snippets');?></div>
	<?php endif; ?>

	</div>

</section>