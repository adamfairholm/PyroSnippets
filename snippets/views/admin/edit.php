<h3><?php echo sprintf(lang('snippets.edit_snippet'), $snippet->name);?></h3>

<?php echo form_open($this->uri->uri_string(), 'class="crud"'); ?>

<div class="tabs">

	<ul class="tab-menu">
		<li><a href="#snippet-content-tab"><span><?php echo lang('snippets.content');?></span></a></li>
		<?php if(group_has_role('snippets', 'admin_snippets')): ?><li><a href="#snippet-data-tab"><span><?php echo lang('snippets.setup');?></span></a></li><?php endif; ?>
	</ul>

	<div id="snippet-content-tab">
	
		<ol>
			<?php if ($snippet->type != 'image'):?>
			<li>
				<label for="name"><?php echo lang('snippets.snippet_content');?></label><br />
				<?php echo form_textarea('content', $snippet->content, 'width="400" class="wysiwyg-advanced"'); ?>
				<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
			</li>
			<?php else: ?>
			<li>
				<label for="name"><?php echo lang('snippets.snippet_'.$snippet->type);?></label><br />
				<?php echo form_dropdown('content', $images, $snippet->content); ?>
				<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
			</li>
			<?php endif; ?>

		</ol>
		
	</div><!--#snippet-content-tab-->

	<?php if(group_has_role('snippets', 'admin_snippets')): ?><div id="snippet-data-tab">
	
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
		
	</div><!--#snippet-data-tab--><?php endif; ?>

</div><!--tabs-->

<br />

<div class="float-right buttons">
<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))); ?>
</div>

<?php echo form_close(); ?>