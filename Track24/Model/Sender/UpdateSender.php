<?php

/**
 * @author      Litslink
 * @category    Litslink
 * @package     Litslink_Track24
 * @copyright   Copyright (c) 2016
 */
namespace Litslink\Track24\Model\Sender;

use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Email\Container\CreditmemoIdentity;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\Sender;
use Magento\Sales\Model\ResourceModel\Order\Creditmemo as CreditmemoResource;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Framework\Event\ManagerInterface;
use \Litslink\Track24\Model\Track24 as Track24;

/**
 * Class UpdateSender
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpdateSender extends Sender
  implements
    \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'track24_sender';

    /**
     * @var PaymentHelper
     */
    protected $paymentHelper;

    /**
     * @var CreditmemoResource
     */
    protected $creditmemoResource;

    /**
     * Global configuration storage.
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $globalConfig;

    /**
     * @var Renderer
     */
    protected $addressRenderer;

    /**
     * Application Event Dispatcher
     *
     * @var ManagerInterface
     */
    protected $eventManager;

    /**
     * @param Template $templateContainer
     * @param CreditmemoIdentity $identityContainer
     * @param Order\Email\SenderBuilderFactory $senderBuilderFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param PaymentHelper $paymentHelper
     * @param CreditmemoResource $creditmemoResource
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig
     * @param Renderer $addressRenderer
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        Template $templateContainer,
        CreditmemoIdentity $identityContainer,
        \Magento\Sales\Model\Order\Email\SenderBuilderFactory $senderBuilderFactory,
        \Psr\Log\LoggerInterface $logger,
        Renderer $addressRenderer,
        Track24 $track24,
        PaymentHelper $paymentHelper,
        CreditmemoResource $creditmemoResource,
        \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig,
        ManagerInterface $eventManager
    ) {
        parent::__construct($templateContainer, $identityContainer, $senderBuilderFactory, $logger, $addressRenderer);
        $this->paymentHelper = $paymentHelper;
        $this->creditmemoResource = $creditmemoResource;
        $this->globalConfig = $globalConfig;
        $this->addressRenderer = $addressRenderer;
        $this->eventManager = $eventManager;
        $this->_track24 = $track24;
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function testEmailSend()
    {
      return;
      $headers = "From: admin@devlits.com\r\n";

      $is_sent = 
        mail('andrew33@masterdef.net', 'subject-test-email', 
          'long text email here, with comments from sender module', $headers);

      //var_dump(53625, $is_sent);
    }

    public function send(Order $order, $forceSyncMode = false)
    {
        //$this->testEmailSend();

        $transport = [
            'order' => $order,
            'billing' => $order->getBillingAddress(),
            'store' => $order->getStore()
        ];

        $this->templateContainer->setTemplateVars($transport);

        $this->identityContainer->setStore($order->getStore());
        if (!$this->identityContainer->isEnabled()) {
            return false;
        }

        $this->templateContainer->setTemplateOptions($this->getTemplateOptions());

        $templateId = $this->_track24->getNotificationTemplateId();
        //$templateId = $this->identityContainer->getGuestTemplateId();
        $customerName = $order->getBillingAddress()->getName();
        //var_dump(63525, $templateId, $customerName);exit;

        $this->identityContainer->setCustomerName($customerName);
        $this->identityContainer->setCustomerEmail($order->getCustomerEmail());
        $this->templateContainer->setTemplateId($templateId);

        /** @var SenderBuilder $sender */
        $sender = $this->getSender();

        try {
            $sender->send();
            $sender->sendCopyTo();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return false;
    }
}

