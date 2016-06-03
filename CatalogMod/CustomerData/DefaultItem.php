<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Litslink\CatalogMod\CustomerData;

/**
 * Default item
 */
class DefaultItem extends \Magento\Checkout\CustomerData\DefaultItem
{
    /**
     * {@inheritdoc}
     */
    protected function doGetItemData()
    {
        $imageHelper = $this->imageHelper->init($this->getProductForThumbnail(), 'mini_cart_product_thumbnail');
        $_product = $this->item->getProduct()->load($this->item->getProduct()->getId());


        $category_model = null;
        $categories = $_product->getCategoryCollection($_product);
        foreach ($categories as $category) $category_model = $category;
        $categories = $_product->getAvailableInCategories();
        $categories_txt = array();
        foreach ($categories as $category_id)
        {
          $categories_txt[] = $category_model->load($category_id)->getName();
          //var_dump(106315, $category->load($category->getId())->getData());exit;
        }

        $slots = explode(',', $_product->getSlots());

        return [
            'options' => $this->getOptionList(),
            'qty' => $this->item->getQty() * 1,
            'item_id' => $this->item->getId(),
            'configure_url' => $this->getConfigureUrl(),
            'is_visible_in_site_visibility' => $this->item->getProduct()->isVisibleInSiteVisibility(),
            'slots' => $slots,
            'categories' => $categories_txt,
            'product_name' => $this->item->getProduct()->getName(),
            'product_url' => $this->getProductUrl(),
            'product_has_url' => $this->hasProductUrl(),
            'product_price' => $this->checkoutHelper->formatPrice($this->item->getCalculationPrice()),
            'product_image' => [
                'src' => $imageHelper->getUrl(),
                'alt' => $imageHelper->getLabel(),
                'width' => $imageHelper->getWidth(),
                'height' => $imageHelper->getHeight(),
            ],
            'canApplyMsrp' => $this->msrpHelper->isShowBeforeOrderConfirm($this->item->getProduct())
                && $this->msrpHelper->isMinimalPriceLessMsrp($this->item->getProduct()),
        ];
    }
}
