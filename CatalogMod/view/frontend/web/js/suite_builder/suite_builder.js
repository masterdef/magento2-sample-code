define([
  'underscore',
  'jquery',
  'ko',
  'Magento_Customer/js/customer-data',
  'uiComponent',
  './draggable',
  'sidebar'
], function(_, $, ko, storage, Component, drake) {
  'use strict';

  var miniCartSelector = '[data-block="minicart"]';

  var isPending = ko.observable(false);
  var isHidden = ko.observable(false);
  var state = ko.observable();

  var slotMap = [
    [29, ['helmets']],
    [32, ['elbows mini', 'elbows right mini']],
    [33, ['hand mini', 'hand right mini']],
    [34, ['neck mini']],
    [35, ['shoulders', 'shoulders right']],
    [36, ['body']],
    [37, ['bracers', 'bracers right']],
    [27, ['hips', 'hips right']],
    [28, ['knees mini', 'knees right mini']],
    [38, ['shin', 'shin right']],
    [39, ['foot', 'foot right']],
    [40, ['shield']],
    [41, ['sword']]
  ];

  var productAjax = $.mage.sidebar.prototype._ajax;
  var urls = {
    checkout: window.checkout.checkoutUrl,
    update: window.checkout.updateItemQtyUrl,
    remove: window.checkout.removeItemUrl
  };

  var updateState = function(slot) {
    if (slot.count() > 1) {
      slot.nextProduct();

      state(slot.getCurrentProduct());
    } else {
      clearState();
    }
  };

  var clearState = function() {
    state(null);
  };

  var showLoader = function() {
    isPending(true);
  };

  var Product = function(data, isDummy) {
    this.slot = null;
    this.data = data;
    this.isDummy = isDummy;

    this.clearState = clearState;

    this.setSlot = function(slot) {
      this.slot = slot;
    };

    this.getCategoryName = function() {
      var categories = this.data.categories || [];

      return categories[1] || '';
    };

    this.delete = function(vm, event) {
      showLoader();

      productAjax(urls.remove, {
        item_id: data.item_id
      }, $(event.target), $.noop);

      updateState(this.slot);
    };

    this.increment = function(vm, event) {
      showLoader();

      productAjax(urls.update, {
        item_id: data.item_id,
        item_qty: data.qty + 1
      }, $(event.target), $.noop);
    };

    this.decrement = function(vm, event) {
      var qty = (data.qty - 1);

      showLoader();

      productAjax(urls.update, {
        item_id: data.item_id,
        item_qty: qty !== 0 ? qty : 1
      }, $(event.target), $.noop);
    };

    this.next = function() {
      var slot = this.slot;

      slot.nextProduct();
      state(slot.getCurrentProduct());
    };

    this.prev = function() {
      var slot = this.slot;

      slot.prevProduct();
      state(slot.getCurrentProduct());
    };

    _.bindAll(this, 'delete', 'next', 'prev', 'getCategoryName');
  };

  var Slot = function(id, name) {
    this.id = id;
    this.name = name;
    this.products = ko.observableArray();
    this.position = ko.observable(0);
    this.count = ko.computed(function() {
      return this.products().length || 0;
    }.bind(this));

    this.getName = function() {
      return this.name.split(' ')[0];
    };

    this.getId = function() {
      return this.id;
    };

    this.isMirror = function() {
      //@TODO: Write new rule for detect
      return /right/.test(this.name);
    };

    this.addProduct = function(product) {
      this.products.splice(product.isDummy ? 1 : 0, 0, product);
    };

    this.getProducts = function() {
      return this.products();
    };

    this.removeAllProduct = function() {
      this.products([]);
      this.position(0);
    };

    this.getCurrentProduct = function() {
      return this.products()[this.position()];
    };

    this.handleImageClick = function() {
      state(this.getCurrentProduct());
    };

    this.nextProduct = function() {
      var next = this.position() + 1;

      this.position(next > this.count() - 1 ? 0 : next);
    };

    this.prevProduct = function() {
      var prev = this.position() - 1;

      this.position(prev < 0 ? this.count() - 1 : prev);
    };

    _.bindAll(this, 'nextProduct', 'prevProduct', 'handleImageClick',
      'getCurrentProduct', 'removeAllProduct', 'addProduct',
      'getName', 'getId', 'getProducts');
  };

  return Component.extend({
    initialize: function() {
      this._super();

      this.state = state;
      this.isHidden = isHidden;
      this.isPending = isPending;
      this.basket = storage.get('cart');
      this.slots = _.reduce(slotMap, function(memo, item) {
        var id = item[0];
        var slots = item[1];

        _.each(slots, function(name) {
          memo.push(new Slot(id, name));
        });

        return memo;
      }, []);

      _.bindAll(this, 'processBasket', 'addProduct',
        'removeProduct', 'matchWithState');

      this.processBasket(this.basket());
      this.basket.subscribe(this.processBasket);

      drake.on('drop', function() {
        isPending(true);
      });

      // subscribe on cart updating
      $(miniCartSelector)
        .on('contentLoading', function() {
          isPending(true);
        });
    },

    toggle: function() {
      this.isHidden(!this.isHidden());
    },

    processBasket: function(basket) {
      isPending(false);
      this.removeProduct();

      _.each(basket.items, function(product) {
        var slots = product.slots || [];
        _.each(slots, function(slotId, index) {
          if (slotId) {
            this.addProduct(slotId, product, index !== 0);
          }
        }.bind(this));
      }.bind(this));
    },

    addProduct: function(slotId, data, isDummy) {
      var product;

      _.each(this.slots, function(slot) {
        if (slot.getId() == slotId) {
          product = new Product(data, isDummy);
          product.setSlot(slot);
          slot.addProduct(product);
        }
      });

      this.matchWithState(product);
    },

    removeProduct: function() {
      _.each(this.slots, function(slot) {
        slot.removeAllProduct();
      });
    },

    removeAllProductFromBasket: function(vm, event) {
      if (isPending()) {
        return;
      }

      showLoader();

      _.each(this.slots, function(slot) {
        if (slot.isMirror()) {
          return;
        }

        var products = slot.getProducts();
        _.each(products, function(product) {
          if (product.isDummy) {
            return;
          }

          productAjax(urls.remove, {
            item_id: product.data.item_id
          }, $(event.target), clearState);
        });
      });
    },

    handleCheckoutClick: function() {
      location.href = urls.checkout;
    },

    matchWithState: function(newProduct) {
      var product = state();

      if (product &&
        product.slot.id === newProduct.slot.id &&
        product.data.item_id === newProduct.data.item_id) {
        state(newProduct);
      }
    }
  });
});
