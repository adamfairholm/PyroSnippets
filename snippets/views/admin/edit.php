<section class="title">
	<h4><?php echo sprintf(lang('snippets.edit_snippet'), $snippet->name);?></h4>
</section>

<section class="item">

<?php echo form_open($this->uri->uri_string(), 'class="crud"'); ?>

	<ul>
		<li>
			<label for="name"><?php echo lang('snippets.snippet_content');?></label> <span class="required-icon tooltip"><?php echo lang('required_label');?></span>
			<?php echo form_textarea('content', $snippet->content, 'width="400" class="wysiwyg-advanced"'); ?>
			
		</li>
	</ul>
		

	<button type="submit" name="btnAction" value="save" class="btn blue">Save</button>
	<button type="submit" name="btnAction" value="save_exit" class="btn blue">Save &amp; Exit</button>
	<a href="<?php echo site_url('admin/snippets');?>" class="btn gray">Cancel</a>

	<?php echo form_close(); ?>

</section>