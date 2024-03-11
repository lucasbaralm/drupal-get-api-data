(function ($) {
  Drupal.behaviors.ajaxpagination = {
    attach: function (context, settings) {
		$('.pager__link').click(function() {
			console.log('hello world');
			var href = $(this).attr('href');
			if(href && href != '#'){ // Remove active Page onclick         
			  $.ajax({
				url: href, 
				type: "GET",
				success: function(data) {          
				  $('#page-wrapper').html(data);
				  Drupal.attachBehaviors("#page-wrapper");
				}
			  });
			}
			return false;
		});

	}
  };
})(jQuery);