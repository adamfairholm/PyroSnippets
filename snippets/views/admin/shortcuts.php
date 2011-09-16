<nav id="shortcuts">
	<h6><?php echo lang('cp_shortcuts_title')?></h6>
	<ul>
		<li><?php echo anchor('admin/snippets/list_snippets', lang('snippets.list_snippets'), array('class'=>'snippets')) ?></li>
		<?php if(group_has_role('snippets', 'admin_snippets')): ?><li><?php echo anchor('admin/snippets/create_snippet', lang('snippets.add_snippet'), array('class'=>'add')) ?></li><?php endif; ?>
	</ul>
	<br class="clear-both" />
</nav>