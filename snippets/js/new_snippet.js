jQuery(document).ready(function() {

	pyro.generate_slug('#name', '#slug');

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
				jQuery('.form_inputs ul').append(returned_html);
				pyro.chosen();
			}
		});	
	  
	});

});
