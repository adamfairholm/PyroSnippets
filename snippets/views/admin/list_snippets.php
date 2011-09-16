<h3><?php echo lang('snippets.list_snippets'); ?></h3>				
	
	<div class="box-container">	
	
		<?php if (!empty($snippets)): ?>
				
			<table border="0" class="table-list">    
				<thead>
					<tr>
						<th><?php echo lang('snippets.snippet_name'); ?></th>
						<th><?php echo lang('snippets.snippet_type'); ?></th>
						<th>Syntax</th>
						<th>Actions</th>
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
							<td><?php echo $snippet->type; ?></td>
							<td>{pyro:snippet:<?php echo $snippet->slug; ?>}</td>
							<td>
								<?php echo anchor('admin/snippets/edit_snippet/' . $snippet->id, 'Edit');?> | 
								<?php echo anchor('admin/snippets/delete_snippet/' . $snippet->id, 'Delete', array('class'=>'confirm')); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>	
			</table>
			
		<?php else: ?>
			<p><?php echo lang('snippets.no_snippets');?></p>
		<?php endif; ?>
	</div>