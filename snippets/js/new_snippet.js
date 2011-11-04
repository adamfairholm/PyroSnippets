function slugify(str){return str.toLowerCase().replace(/-+/g, '').replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');}

jQuery(document).ready(function() {

	$('#name').keyup(function() { $('#slug').val(slugify($('#name').val())); });

	// Add fields
	$('#type').change(function() {
	
		var snippet_slug = $('#type').val();
		var snippet_id = $('#snippet_id').val();
		
		jQuery.ajax({
			dataType: "text",
			type: "POST",
			data: 'snippet_slug='+snippet_slug+'&snippet_id='+snippet_id,
			url:  SITE_URL+'admin/snippets/setup/snippet_parameters',
			success: function(returned_html){
				jQuery('.temp_row').remove();
				jQuery('#snippet_form').append(returned_html);
				pyro.chosen();
			}
		});	
	  
	});

});
