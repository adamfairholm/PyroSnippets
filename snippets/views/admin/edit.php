<section class="title">
	<h4><?php echo sprintf(lang('snippets.edit_snippet'), $snippet->name);?></h4>
</section>

<section class="item">

<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

	<ul>
		<li>
			<label for="name"><?php echo lang('snippets.snippet_content');?></label> <span class="required-icon tooltip"><?php echo lang('required_label');?></span>
			<?php echo $this->snippets_m->snippets->{$snippet->type}->form_output($snippet->content); ?>
		</li>
	</ul>
		
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel') )); ?>

	<?php echo form_close(); ?>

</section>