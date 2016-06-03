<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Litslink\CustomForm\Block;

use Magento\Framework\View\Element\Template;

/**
 * Main contact form block
 */
class ContactForm extends Template
{
    /**
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
    }

    /**
     * Returns action url for contact form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('customform/index/post', ['_secure' => true]);
    }

    protected function _prepareLayout()
    {
        $this->_addBreadcrumbs();
    }

    /**
     * Prepare breadcrumbs
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function _addBreadcrumbs()
    {
        //var_dump('addcrumb-137');exit;
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $breadcrumbs
              ->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
              )
              ->addCrumb(
                'orders',
                [
                  'label' => $this->pageConfig->getTitle()->get(),
                  'title' => $this->pageConfig->getTitle()->get(),
                ]
              )
              ;
        }
    }
}
