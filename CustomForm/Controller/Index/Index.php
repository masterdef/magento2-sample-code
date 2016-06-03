<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Litslink\CustomForm\Controller\Index;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\View\Result\PageFactory;
//use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Action\Context;

/**
 * Responsible for loading page content.
 *
 * This is a basic controller that only loads the corresponding layout file. It may duplicate other such
 * controllers, and thus it is considered tech debt. This code duplication will be resolved in future releases.
 */
class Index extends \Magento\Framework\App\Action\Action
{
    protected $pageFactory;

    public function __construct(
        Context $context,
        //\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        PageFactory $resultPageFactory
    )
    {
        $this->pageFactory = $resultPageFactory;
        //$this->scopeConfig = $scopeConfig;
        //$this->_addBreadcrumbs();
        return parent::__construct($context);
    }
 
    public function execute()
    {        
        //var_dump(__METHOD__);
        $page_object = $this->pageFactory->create();;
        return $page_object;
    }    

}
