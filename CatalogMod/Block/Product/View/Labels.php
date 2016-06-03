<?php
/**
 * @author      Litslink
 * @category    Litslink
 * @package     Litslink_CatalogMod
 * @copyright   Copyright (c) 2016
 */
namespace Litslink\CatalogMod\Block\Product\View;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Helper\Image;
use \Magento\Catalog\Block\Product\Context;

class Labels extends \Magento\Framework\View\Element\Template 
{
    protected $_product = null;


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $productContext,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_imageHelper = $productContext->getImageHelper();
        $GLOBALS['_product_id'] = $this->getProduct()->getId();
        parent::__construct($context, $data);
    }


    /**
     * Initialize data and prepare it for output
     *
     * @return string
     */
    protected function _beforeToHtml()
    {
        $this->prepareBlockData();
        return parent::_beforeToHtml();
    }

    public function getProduct()
    {
      return $this->_coreRegistry->registry('product');
    }

    public function getLabelCssClasses()
    {
      $css_classes = '';
      $labels_list = $this->getAtLabels();

      foreach (explode(',', $labels_list) as $label_id) 
      {
        if ($this->getProduct()->getData($label_id)) $css_classes .= 'label-' . $label_id;
        //var_dump(1134219, $label_id, $this->getProduct()->getData($label_id));exit;
      }

      return $css_classes;
    }

    /**
     * Prepares block data
     *
     * @return void
     */
    protected function prepareBlockData()
    {
        $this->addData(
            [
                'product_id'  => $this->getProduct()->getId()
            ]
        );
    }
}

