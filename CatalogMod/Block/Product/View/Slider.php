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

/**
 * One page checkout success page
 */
class Slider extends  \Magento\Framework\View\Element\Template //\Magento\Catalog\Block\Product\View\AbstractView
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

    public function getGalleryImages($type = '')
    {
        $product = $this->getProduct();
        $mediaAttributes = $product->getMediaAttributeValues();
        $images = $product->getMediaGallery('images');
        $images2 = array();
        //var_dump(903216, ($images), $product->getMediaAttributeValues());exit;

        foreach ($images as $image) 
        {
            if ($type && $type != $image['label']) continue;

            //var_dump(858216, $image);exit;
            $image2 = new \Magento\Framework\DataObject();

            /* @var \Magento\Framework\DataObject $image */
            $image2->setData(
                'small_image_url',
                $this->_imageHelper->init($product, 'product_page_image_small')
                    ->setImageFile($image['file'])
                    ->getUrl()
            );

            $image2->setData(
                'medium_image_url',
                $this->_imageHelper->init($product, 'product_page_image_medium')
                    ->constrainOnly(true)->keepAspectRatio(true)->keepFrame(true)
                    ->resize(400,300)
                    ->setImageFile($image['file'])
                    ->getUrl()
            );

            $image2->setData(
                'large_image_url',
                $this->_imageHelper->init($product, 'product_page_image_large')
                    ->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false)
                    ->setImageFile($image['file'])
                    ->getUrl()
            );

            $images2[] = $image2;
        }

        return $images2;
    }

    public function getProduct()
    {
      return $this->_coreRegistry->registry('product');
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

