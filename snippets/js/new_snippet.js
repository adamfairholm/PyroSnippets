function slugify(str)
{
	var url = str.toLowerCase().replace(/^\s+|\s+$/g, "").replace(/[_|\s]+/g, "-").replace(/[^a-z0-9-]+/g, "").replace(/[-]+/g, "-").replace(/^-+|-+$/g, "");
	
	return url;
}

jQuery(document).ready(function() {

	$('#name').keyup(function() {
  
 	 	$('#slug').val(slugify($('#name').val()));
 	   
	});

});
