(function($) {
	$(function(){

		form = $('form.crud');
		
		$('input[name="name"]', form).keyup($.debounce(300, function(){
		
			slug = $('input[name="slug"]', form);
			
			$.post(BASE_URI + 'index.php/admin/snippets/stream_slug', { title : $(this).val() }, function(new_slug){
				slug.val( new_slug );
			});
		}));
				
	});
})(jQuery);