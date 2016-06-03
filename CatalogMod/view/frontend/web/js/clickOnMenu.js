(function($){
  console.log('clickonmenu.js');

  $('.popup-menu .categories a').mouseover(function(){
    $('.popup-menu .categories-items').hide();
    $('.popup-menu #' + $(this).attr('rel')).show();
  });

  var myBody = $('body'),
    logo = $('.logo'),
    popupMenu = $('.popup-menu'),
    menuBackground = $('.menu-background'),
    initialPositionProductDataItems = 0;//$('.product.data.items').position().top;


  function hideMenu() {
    popupMenu.removeClass('active');
    menuBackground.removeClass('active');
  }

  $('.action.nav-toggle').click(function(event) {
    event.preventDefault();
    event.stopPropagation();
    if (!popupMenu.hasClass('active')) {
      popupMenu.addClass('active');
      menuBackground.addClass('active');
    } else {
      popupMenu.removeClass('active');
      menuBackground.removeClass('active');
    }
  });

  menuBackground.click(function() {
    hideMenu();
  });

  myBody.keydown(function(e) {
    if (e.keyCode == 27) {
      hideMenu();
    }
  });

  function addingFixedClasses(event) {
    var productDataItems = $('.product.data.items');
    if (productDataItems.length)
    {
      var currentScreenPosition = $(document).scrollTop(),
        productDataItems = $('.product.data.items'),

          productDataItemsPosition = productDataItems.position().top,
          pageFooterPosition = $('.page-footer').position().top;

      if (currentScreenPosition > initialPositionProductDataItems) {
        //productDataItems.addClass('fixed');
      } else {
        //productDataItems.removeClass('fixed');
      };
    }


    var el1 = $('.review-add .block-content');
    if (el1.length)
    {
      var el1 = $('.review-add .block-content'),
        el1BotPos = el1.offset().top + el1.outerHeight(),
        fixedMan = $('.fixed-man'),
        fixedManBotPos = fixedMan.offset().top + fixedMan.outerHeight()
        ;

      if (el1BotPos <= fixedManBotPos) {
        fixedMan.addClass('bottom-fixed');
      } else {
        fixedMan.removeClass('bottom-fixed');
      };
    }

  }

  $(window).scroll(addingFixedClasses);
  $(window).ready(addingFixedClasses);
})(jQuery);

