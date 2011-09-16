<h3><?php echo sprintf(lang('snippets.edit_snippet'), $snippet->name);?></h3>

<?php echo form_open($this->uri->uri_string(), 'class="crud"'); ?>

<div class="tabs">

	<ul class="tab-menu">
		<li><a href="#snippet-content-tab"><span><?php echo lang('snippets.content');?></span></a></li>
		<li><a href="#snippet-data-tab"><span><?php echo lang('snippets.setup');?></span></a></li>
	</ul>

	<div id="snippet-content-tab">
	
		<ol>

			<li>
				<label for="name"><?php echo lang('snippets.snippet_content');?></label><br />
				<?php echo form_textarea('content', $snippet->content, 'width="400" class="wysiwyg-advanced"'); ?>
				<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
			</li>

		</ol>
		
	</div><!--#snippet-content-tab-->

	<div id="snippet-data-tab">
	
		<ol>

			<li>
				<label for="name"><?php echo lang('snippets.snippet_name');?></label>
				<?php echo form_input('name', htmlspecialchars_decode($snippet->name), 'maxlength="60"'); ?>
				<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
			</li>

			<li class="even">
				<label for="slug"><?php echo lang('snippets.snippet_slug');?></label>
				<?php echo form_input('slug', $snippet->slug, 'maxlength="60"'); ?>
				<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
			</li>

			<li>
				<label for="type"><?php echo lang('snippets.snippet_type');?></label>
				<?php echo form_dropdown('type', $snippet_types, $snippet->type); ?>
			</li>
		
		</ol>
		
	</div><!--#snippet-data-tab-->

</div><!--tabs-->

<br />

<div class="float-right buttons">
<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
</div>

<?php echo form_close(); ?>