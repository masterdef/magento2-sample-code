<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Litslink\CustomerMod\Controller\Mysizes;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Customer\Model\Session;

/**
 * Responsible for loading page content.
 *
 * This is a basic controller that only loads the corresponding layout file. It may duplicate other such
 * controllers, and thus it is considered tech debt. This code duplication will be resolved in future releases.
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /** @var CustomerRepositoryInterface  */
    protected $customerRepository;

    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /**
     * @var Session
     */
    protected $session;
    protected $pageFactory;

    public function __construct(
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        DataObjectHelper $dataObjectHelper,
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        $this->session = $customerSession;
        $this->resultPageFactory = $resultPageFactory;
        $this->customerRepository = $customerRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->pageFactory = $resultPageFactory;
        return parent::__construct($context);
    }
 
    public function execute()
    {        
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $block = $resultPage->getLayout()->getBlock('customer_edit');
        if ($block) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }

        $data = $this->session->getCustomerFormData(true);
        $customerId = $this->session->getCustomerId();
        var_dump(30034, $customerId, get_class($this->session), $this->session->getData());exit;
        $customerDataObject = $this->customerRepository->getById($customerId);
        if (!empty($data)) {
            $this->dataObjectHelper->populateWithArray(
                $customerDataObject,
                $data,
                '\Magento\Customer\Api\Data\CustomerInterface'
            );
        }
        $this->session->setCustomerData($customerDataObject);
        $this->session->setChangePassword($this->getRequest()->getParam('changepass') == 1);

        $resultPage->getConfig()->getTitle()->set(__('Account Information'));
        $resultPage->getLayout()->getBlock('messages')->setEscapeMessageFlag(true);
        return $resultPage;
    }    
}

