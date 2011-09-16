<h3><?php echo lang('snippets.list_snippets'); ?></h3>	

	<div class="box-container">	
	
		<?php if (!empty($snippets)): ?>
				
			<table border="0" class="table-list">    
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
							<td>{pyro:snippet:<?php echo $snippet->slug; ?>}</td>
							<td class="align-center buttons buttons-small">
								<a href="<?php echo site_url('admin/snippets/edit_snippet/'.$snippet->id);?>" class="button edit">Edit</a>
								<a href="<?php echo site_url('admin/snippets/delete_snippet/'.$snippet->id);?>" class="confirm button delete">Delete</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>	
			</table>
			
		<?php else: ?>
			<p><?php echo lang('snippets.no_snippets');?></p>
		<?php endif; ?>
	</div>