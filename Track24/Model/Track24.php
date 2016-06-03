<?php

/**
 * @author      Litslink
 * @category    Litslink
 * @package     Litslink_Track24
 * @copyright   Copyright (c) 2016
 */
namespace Litslink\Track24\Model;

use Magento\Framework\Translate\Inline\ConfigInterface;

/**
 * Class Track24
 *
 */
class Track24 extends \Magento\Sales\Model\Order\Shipment
{
    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\View\DesignInterface $viewDesign
     * @param \Magento\Framework\App\DesignInterface $design
     * @param \Magento\Framework\TranslateInterface $translate
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param ConfigInterface $inlineConfig
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     * @param \Psr\Log\LoggerInterface $logger
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        //parent::__construct($data);
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
    }

    public function getNotificationTemplateId()
    {
        $tpl = $this->_scopeConfig->getValue(
            'track24/api_details/status_update_template',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $tpl;
    }


    /**
     * @return string
     */
    public function getTrackingUrl($tracking_number)
    {
        $api_key = $this->_scopeConfig->getValue(
            'track24/api_details/api_publickey',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $domain = 'ageofcraft.com';

        
        $track_url = $this->_storeManager->getStore()->getBaseUrl() . "/track24.php?1=1"; //apiKey={$api_key}&domain={$domain}";
        //$track_url = "{$track_url}&code={$tracking_number}";
        $url_hash = "https://track24.ru/api/tracking.json.php?apiKey={$api_key}"
          . "&domain={$domain}&code={$tracking_number}";
        $track_url = "{$track_url}&url_hash=" . base64_encode($url_hash);

      return $track_url;
    }

    public function getTrackingData($tracking_number)
    {
      $data = implode('', file($this->getTrackingUrl($tracking_number)));
      return $data;
    }
}

