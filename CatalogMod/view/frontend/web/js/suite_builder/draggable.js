define(['jquery', 'dragula'], function($, dragula) {
  var products = document.querySelector('.products-grid .items.product-items');
  var product = document.querySelector('.fotorama__stage__shaft.fotorama__grab');
  var slider = document.querySelector('.product-items ul.bxslider-related');

  var drake = dragula([products, product, slider], {
    copy: true,
    revertOnSpill: true,

    isContainer: function(element) {
      return element.classList.contains('suite-builder__suite');
    },

    moves: function(element, container, handle) {
      var classList = handle.classList;

      return classList.contains('product-image-photo') || classList.contains('fotorama__img');
    },

    accepts: function() {
      return true;
    }
  });

  drake.on('drop', function(element) {
    var form = $(element).find('form[data-role="tocart-form"]');
    var addToCartButton = $('#product-addtocart-button');

    if (form.length) {
      $.mage.catalogAddToCart.prototype.ajaxSubmit(form); // for catalog page
    } else {
      addToCartButton.click(); //for product page
    }

    drake.cancel();
  });

  return drake;
});
