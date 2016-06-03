<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Layered navigation state
 */
namespace Litslink\CustomerMod\Block\Search;

use Magento\Framework\View\Element\Template;
use \Magento\Catalog\Model\Product\Attribute\Repository as AttributeRepository;

class Title extends \Magento\Framework\View\Element\Template
{
    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    protected $_catalogLayer;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        AttributeRepository $attributeRepository,
        array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_attributeRepository = $attributeRepository;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve active filters
     *
     * @return array
     */
    public function getActiveFilters()
    {
        /*
        $filters = $this->getLayer()->getState()->getFilters();
        if (!is_array($filters)) {
            $filters = [];
        }

        //var_dump(359217, count($filters));exit;
        //$filters = $this->getActiveFilters();
        foreach ($filters as $_filter)
        {
          $this->pageConfig->getTitle()->set(
            $this->pageConfig->getTitle()->get()
            . ' '
            . $_filter->getName() . ': ' . $_filter->getLabel());
        }
        //$this->pageConfig->getTitle()->set(911111);
        */

        if (@$_GET['country'])
        {
          $country = $_GET['country'];
          $options = $this->_attributeRepository->get('country')->getOptions();
          foreach ($options as $option)
          {
            if ($option->getValue() == $country)
            {
              //var_dump(923211, $option->getLabel());exit;
              $this->pageConfig->getTitle()->set(
                $this->pageConfig->getTitle()->get()
                . ' '
                . 'Region: ' . $option->getLabel());
            }
          }
        }

        if (@$_GET['steel'])
        {
          $country = $_GET['steel'];
          $options = $this->_attributeRepository->get('steel')->getOptions();
          foreach ($options as $option)
          {
            if ($option->getValue() == $country)
            {
              //var_dump(923211, $option->getLabel());exit;
              $this->pageConfig->getTitle()->set(
                $this->pageConfig->getTitle()->get()
                . ' '
                . 'Steel: ' . $option->getLabel());
            }
          }
        }

        $this->pageConfig->force_title_set = $this->pageConfig->getTitle()->get();
        //var_dump(359219, $this->pageConfig->getTitle()->get(), $this->_attributeRepository->get('country')->getOptions());exit;

        //return $filters;
    }


    protected function _prepareLayout()
    {
        $this->getActiveFilters();
        /*
        // add Home breadcrumb
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs) {
            $breadcrumbs
              ->addCrumb(
                'home',
                [
                    'label' => __('Dashboard'),
                    'title' => __('Dashboard'),
                    'link' => '/customer/account/'
                ]
              )
              ->addCrumb(
                'orders',
                [
                    'label' => __('My Orders'),
                    'title' => __('My Orders'),
                ]
              )
              ;
        }
        */
    }

    /**
     * Retrieve Clear Filters URL
     *
     * @return string
     */
    public function getClearUrl()
    {
        $filterState = [];
        foreach ($this->getActiveFilters() as $item) {
            $filterState[$item->getFilter()->getRequestVar()] = $item->getFilter()->getCleanValue();
        }
        $params['_current'] = true;
        $params['_use_rewrite'] = true;
        $params['_query'] = $filterState;
        $params['_escape'] = true;
        return $this->_urlBuilder->getUrl('*/*/*', $params);
    }

    /**
     * Retrieve Layer object
     *
     * @return \Magento\Catalog\Model\Layer
     */
    public function getLayer()
    {
        if (!$this->hasData('layer')) {
            $this->setLayer($this->_catalogLayer);
        }
        return $this->_getData('layer');
    }
}

