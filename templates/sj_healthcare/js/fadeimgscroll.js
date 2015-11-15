jQuery(document).ready(function($){
	img = $("#bd img").css({ "opacity":"0","visibility":"hidden"  });
	
	function fade_img(){
		img.each(function(i) {
			a = $(this).offset().top + $(this).height();
			b = $(window).scrollTop() + $(window).height();
			if (a < b) $(this).css({ "opacity":"1","visibility":"visible"  });
		});
	}
	
	$(window).load(function(d,h){fade_img();});
	
	$(window).scroll(function(d,h) {fade_img();});


});