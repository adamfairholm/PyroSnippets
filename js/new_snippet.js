jQuery(document).ready(function() {

	pyro.generate_slug('#name', '#slug', '_');

	// Add fields
	$('#type').change(function() {
	
		var snippet_slug = $('#type').val();
		var snippet_id = $('#snippet_id').val();
		
		$.ajax({
			dataType: "text",
			type: "POST",
			data: { snippet_slug : snippet_slug, snippet_id : snippet_id },
			url:  SITE_URL+'admin/snippets/setup/snippet_parameters',
			success: function(returned_html){
				$('.snip_parameters').remove();
				$('#form_inputs').append(returned_html);
				pyro.chosen();
			}
		});	
	  
	});

});
