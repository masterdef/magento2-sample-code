/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
  paths: {
    ageofcraft: 'Litslink_CatalogMod/js/ageofcraft',
    bxslider: 'Litslink_CatalogMod/js/jquery.bxslider.min',
    clickOnMenu: 'Litslink_CatalogMod/js/clickOnMenu',
    dragula: 'Litslink_CatalogMod/lib/dragula/dragula',
    'suite_builder': 'Litslink_CatalogMod/js/suite_builder/suite_builder'
  },
  shim: {
    'ageofcraft': ['jquery'],
    'bxslider': ['jquery'],
    'clickOnMenu': ['jquery']
  },
  deps: [
    'jquery'
  ]
};
