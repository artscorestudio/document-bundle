$(document).ready(function(){
	$('.doc-title').keyup(function(){
		var slug = getSlug($(this).val());
		$('.doc-slug').val(slug);
	});
});