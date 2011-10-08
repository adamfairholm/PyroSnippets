<section class="title">
	<h4><?php echo sprintf(lang('snippets.edit_snippet'), $snippet->name);?></h4>
</section>

<section class="item">

<?php echo form_open($this->uri->uri_string(), 'class="crud"'); ?>

<div class="tabs">

	<ul class="tab-menu">
		<li><a href="#snippet-content-tab"><span><?php echo lang('snippets.content');?></span></a></li>
		<?php if(group_has_role('snippets', 'admin_snippets')): ?><li><a href="#snippet-data-tab"><span><?php echo lang('snippets.setup');?></span></a></li><?php endif; ?>
	</ul>
	
	<div id="snippet-content-tab">

	<table>
	
			<?php if ($snippet->type != 'image'):?>
			<tr>
				<td class=""><label for="name"><?php echo lang('snippets.snippet_content');?></label></td>
				<td><?php echo form_textarea('content', $snippet->content, 'width="400" class="wysiwyg-advanced"'); ?>
				<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
			</tr>
			<?php else: ?>
			<tr>
				<td class=""><label for="name"><?php echo lang('snippets.snippet_'.$snippet->type);?></label></td>
				<td><?php echo form_dropdown('content', $images, $snippet->content); ?>
				<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
			</tr>
			<?php endif; ?>

	</table>
		
	</div><!--#snippet-content-tab-->

	<?php if(group_has_role('snippets', 'admin_snippets')): ?>
	
	<div id="snippet-data-tab">
	
		<table>

			<tr>
				<td class=""><label for="name"><?php echo lang('snippets.snippet_name');?></label></td>
				<td><?php echo form_input('name', htmlspecialchars_decode($snippet->name), 'maxlength="60"'); ?>
				<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
			</li>

			<tr>
				<td class=""><label for="slug"><?php echo lang('snippets.snippet_slug');?></label></td>
				<td><?php echo form_input('slug', $snippet->slug, 'maxlength="60"'); ?>
				<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
			</li>

			<tr>
				<td class=""><label for="type"><?php echo lang('snippets.snippet_type');?></label></td>
				<td><?php echo form_dropdown('type', $snippet_types, $snippet->type); ?></td>
			</li>
		
		</table>
		
	</div><!--#snippet-data-tab--><?php endif; ?>

</div><!--tabs-->

	<button type="submit" name="btnAction" value="save" class="btn blue"><?php echo lang('snippets.snippet_btn_save');?></button>
	<button type="submit" name="btnAction" value="save_exit" class="btn blue"><?php echo lang('snippets.snippet_btn_saveexit');?></button>
	<a href="<?php site_url('admin/snippets');?>" class="btn gray"><?php echo lang('snippets.snippet_btn_cancel');?></a>

	<?php echo form_close(); ?>

</section>