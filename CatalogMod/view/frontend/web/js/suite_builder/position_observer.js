define(['jquery'], function($) {
  var lastPosition = 0;

  var suiteBuilderContainer = $('.suite-builder__container');
  var contentHeight = $('#maincontent').height();
  var height = suiteBuilderContainer.find('.suite-builder').height();
  var diff = contentHeight - height;

  var track = function() {
    var currentPosition = $(this).scrollTop();

    // hide container on scroll to bottom
    if (currentPosition > diff) {
      suiteBuilderContainer.addClass('hide');
    } else {
      suiteBuilderContainer.removeClass('hide');
    }

    // add offset on scroll to bottom
    if (currentPosition > lastPosition) {
      suiteBuilderContainer.addClass('move-down');
      suiteBuilderContainer.removeClass('move-up');
    } else {
      suiteBuilderContainer.removeClass('move-down');

      // fix position on product page
      if (currentPosition < 200) {
        suiteBuilderContainer.removeClass('move-up');
      } else {
        suiteBuilderContainer.addClass('move-up');
      }
    }

    lastPosition = currentPosition;
  };

  $(window).scroll(_.throttle(track, 50));
});
