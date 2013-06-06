<section class="title">
<?php if($mode == 'create'): ?>
	<h4><?php echo lang('snippets.add_snippet') ;?></h4>
<?php else: ?>
	<h4><?php echo sprintf(lang('snippets.edit_snippet'), $snippet->name);?></h4>

<?php endif; ?>
</section>

<section class="item">
<div class="content">

<?php echo form_open($this->uri->uri_string(), 'class="crud"'); ?>

<div class="form_inputs">

	<ul id="form_inputs">
		<li>
			<label for="name"><?php echo lang('snippets.snippet_name');?> <span>*</span></label>
			<div class="input"><?php echo form_input('name', htmlspecialchars_decode($snippet->name), 'maxlength="60" id="name"'); ?></div>
		</li>

		<li>
			<label for="slug"><?php echo lang('snippets.snippet_slug');?> <span>*</span></label>
			<div class="input"><?php echo form_input('slug', $snippet->slug, 'maxlength="60" id="slug"'); ?></div>
		</li>
	
		<li>
			<label for="type"><?php echo lang('snippets.snippet_type');?></label>
			<div class="input"><?php echo form_dropdown('type', $this->snippets_m->snippet_array, $snippet->type, 'id="type"'); ?></div>
		</li>
		
		<?php if($mode == 'edit' and isset($this->snippets_m->snippets->{$snippet->type}->parameters)): foreach($this->snippets_m->snippets->{$snippet->type}->parameters as $param): ?>
		
		<li class="snip_parameters">
			<label for="<?php echo $param; ?>"><?php echo $this->lang->line('snippets.param.'.$param); ?></label>
			<?php isset($snippet->params[$param]) ? $val = $snippet->params[$param] : $val = null; ?>
			<div class="input"><?php echo $this->snippets_m->snippets->{$snippet->type}->{'param_'.$param}($val); ?></div>
		</li>
			
		<?php endforeach; endif; ?>
		
	</ul>
	
	<?php 
		
		($mode == 'create') ? $buttons = array('save', 'cancel') : $buttons = array('save', 'save_exit', 'cancel'); 
		
		$this->load->view('admin/partials/buttons', array('buttons' => $buttons))
	
	?>

<?php echo form_close(); ?>

</div><!--.form_inputs-->

</div>
</section>