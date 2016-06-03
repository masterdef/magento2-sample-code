
// ageofcraft-430
// ***
console.log('ageofcraft-4501');



(function($) {
	/***************** Smooth Scrolling ******************/
	$(function() {
	  $('a[href*=#]:not([href=#])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
        && location.hostname == this.hostname) 
      {
        var target_slice = this.hash.slice(1);
        target_slice = target_slice.replace(/\./g, '\\.');
	      var target = $('#' + target_slice);
	      target = target.length ? target : $('[name=' + target_slice +']');
        console.log('scrollto-animate-502', target_slice, target.length);

	      if (target.length) {
	        $('html,body').animate({
	          scrollTop: target.offset().top
	        }, 1000);
	        return false;
	      }
	    } //endif
	  });
	});

	/***************** Product sub menu ******************/
	$(window).bind('scroll', function() {
		 if ($(window).scrollTop() > 550) {
		     $('.detailed').addClass('fixed_menu');

		 }
		 else {
		     $('.detailed').removeClass('fixed_menu');

		 }
	});

  //header scroll
  var lastScrollTop = 0;
  $(window).scroll(function(event){
     var st = $(this).scrollTop();
     if (st > lastScrollTop){
       // down
       $('.page-header').addClass('scrolling_down');
       $('.page-header').removeClass('scrolling_up');
       $('.filter').addClass('filter_scrolling_down');
       $('.filter').removeClass('filter_scrolling_up');
       $('.product_header_line').addClass('line_scrolling_down');
       $('.product_header_line').removeClass('line_scrolling_up');
       $('.fixed_menu_container').addClass('fixed_menu_scrolling_down');
       $('.fixed_menu_container').removeClass('fixed_menu_scrolling_up');


     } else {
        // up
       $('.page-header').addClass('scrolling_up');
       $('.page-header').removeClass('scrolling_down');
       $('.filter').addClass('filter_scrolling_up');
       $('.filter').removeClass('filter_scrolling_down');
       $('.product_header_line').addClass('line_scrolling_up');
       $('.product_header_line').removeClass('line_scrolling_down');
       $('.fixed_menu_container').addClass('fixed_menu_scrolling_up');
       $('.fixed_menu_container').removeClass('fixed_menu_scrolling_down');

     }
     lastScrollTop = st;
  });

  //product menu + scroll
	$(window).bind('scroll', function() {
		 if ($(window).scrollTop() > 500) {
		     $('.product_header_line').addClass('show_line');

		 }
		 else {
		     $('.product_header_line').removeClass('show_line');

		 }
	});

  //product line show/hide options
	$(function() {
		$('.product_options').click(function() {
			$('.catalog-product-view').toggleClass('show_product_options');


    	});

	});





  //SEO block
	$(function() {
		$('.see_more').click(function() {
			var $this = $(this);
			$('.see_more').toggleClass('active');

			$('.page_text_block_item').toggleClass('active');
			$('.overflow_text_block').toggleClass('active');



			$this.toggleClass('see_more');
			if($this.hasClass('see_more')){
				$this.text('See More');
			} else {
				$this.text('Hide text');
			}

    	});
		$(".page_text_block_item.active").click(function () {
			$('.page_text_block_item').removeClass('active');
			$('.overflow_text_block').removeClass('active');

		});

	});



  setInterval(function(){$('.message-success').fadeOut();}, 5000);


  // Show Suite Builder by button click
  var suiteBuilderContainer = $('.suite-builder__container');
  var suiteBuilderToggleButton = $('a.suitebuilder_link');

  suiteBuilderToggleButton.click(function(e) {
    e.preventDefault();
	  suiteBuilderContainer.toggle().toggleClass('show');
  });
})(jQuery);


