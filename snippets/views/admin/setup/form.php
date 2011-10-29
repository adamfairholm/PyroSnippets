<section class="title">
<?php if($mode == 'create'): ?>
	<h4><?php echo lang('snippets.add_snippet') ;?></h4>
<?php else: ?>
	<h4><?php echo sprintf(lang('snippets.edit_snippet'), $snippet->name);?></h4>

<?php endif; ?>
</section>

<section class="item">

<?php echo form_open($this->uri->uri_string(), 'class="crud"'); ?>

	<table id="snippet_form">

		<tr>
			<td width="30%"><label for="name"><?php echo lang('snippets.snippet_name');?></label></td>
			<td><?php echo form_input('name', htmlspecialchars_decode($snippet->name), 'maxlength="60" id="name"'); ?>
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
		</tr>

		<tr>
			<td><label for="slug"><?php echo lang('snippets.snippet_slug');?></label></td>
			<td><?php echo form_input('slug', $snippet->slug, 'maxlength="60" id="slug"'); ?>
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
		</tr>

		<tr>
			<td><label for="type"><?php echo lang('snippets.snippet_type');?></label></td>
			<td><?php echo form_dropdown('type', $this->snippets_m->snippet_array, $snippet->type, 'id="type"'); ?></td>
		</tr>
	
	</table>
	
	<p>
		<input type="hidden" name="snipped_id" id="snippet_id" value="<?php if(isset($snippet->id)) echo $snippet->id; ?>" />
	
		<button type="submit" name="btnAction" value="save" class="btn blue">Save</button>				
		<a href="<?php echo site_url('admin/snippets/setup'); ?>" class="btn gray cancel">Cancel</a>	
	</p>

<?php echo form_hidden('content', ''); ?>

<?php echo form_close(); ?>

</section>