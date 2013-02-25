<section class="title">
	<h4><?php echo lang('snippets.name'); ?></h4>
</section>

<section class="item">
<div class="content">

<?php if ( ! empty($snippets)): ?>
		
	<table border="0" class="table-list">    
		<thead>
			<tr>
				<th><?php echo lang('snippets.snippet_name'); ?></th>
				<th><?php echo lang('snippets.snippet_type'); ?></th>
				<th><?php echo lang('snippets.status'); ?></th>
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
					<td><?php echo $statuses[$snippet->status]; ?></td>
					<td class="actions">
						<a href="<?php echo site_url('admin/snippets/edit_snippet/'.$snippet->id);?>" class="button edit"><?php echo lang('global:edit'); ?></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>	
	</table>
	
<?php else: ?>
	<div class="no_data"><?php echo lang('snippets.no_snippets');?></div><!--.no_data-->
<?php endif; ?>
	
</div>
</section>