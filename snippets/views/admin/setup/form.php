<section class="title">
	<h4><?php echo sprintf(lang('snippets.add_snippet'), $snippet->name);?></h4>
</section>

<section class="item">

<?php echo form_open($this->uri->uri_string(), 'class="crud"'); ?>

	<table>

		<tr>
			<td class=""><label for="name"><?php echo lang('snippets.snippet_name');?></label></td>
			<td><?php echo form_input('name', htmlspecialchars_decode($snippet->name), 'maxlength="60" id="name"'); ?>
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
		</tr>

		<tr>
			<td class=""><label for="slug"><?php echo lang('snippets.snippet_slug');?></label></td>
			<td><?php echo form_input('slug', $snippet->slug, 'maxlength="60" id="slug"'); ?>
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
		</tr>

		<tr>
			<td class=""><label for="type"><?php echo lang('snippets.snippet_type');?></label></td>
			<td><?php echo form_dropdown('type', $this->snippets_m->snippet_array, $snippet->type); ?></td>
		</tr>
	
	</table>
	
	<p>
		<button type="submit" name="btnAction" value="save" class="btn blue">Save</button>				
		<a href="<?php echo site_url('admin/snippets/setup'); ?>" class="btn gray cancel">Cancel</a>	
	</p>

<?php echo form_hidden('content', ''); ?>

<?php echo form_close(); ?>

</section>