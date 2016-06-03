/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
  paths: {
    jupl_uiwidget: '/js/jquery-file-upload/js/vendor/jquery.ui.widget',
    jupl_iframetransp: '/js/jquery-file-upload/js/jquery.iframe-transport',
    jupl_upload: '/js/jquery-file-upload/js/jquery.fileupload',
    jupl_bootstrap: '//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min',
  },
  shim: {
    'jupl_bootstrap': ['jquery'],
  },
  deps: [
    'jquery'
  ]
};
