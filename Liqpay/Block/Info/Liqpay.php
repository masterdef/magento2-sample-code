<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Litslink\Liqpay\Block\Info;

class Liqpay extends \Litslink\Liqpay\Block\Info
{
    /**
     * @var string
     */
    protected $_payableTo;

    /**
     * @var string
     */
    protected $_mailingAddress;

    /**
     * @var string
     */
    protected $_template = 'Litslink_Liqpay::info/liqpay.phtml';

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getPayableTo()
    {
        if ($this->_payableTo === null) {
            $this->_convertAdditionalData();
        }
        return $this->_payableTo;
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getMailingAddress()
    {
        if ($this->_mailingAddress === null) {
            $this->_convertAdditionalData();
        }
        return $this->_mailingAddress;
    }

    /**
     * Enter description here...
     *
     * @return $this
     */
    protected function _convertAdditionalData()
    {
        $details = @unserialize($this->getInfo()->getAdditionalData());
        if (is_array($details)) {
            $this->_payableTo = isset($details['payable_to']) ? (string)$details['payable_to'] : '';
            $this->_mailingAddress = isset($details['mailing_address']) ? (string)$details['mailing_address'] : '';
        } else {
            $this->_payableTo = '';
            $this->_mailingAddress = '';
        }
        return $this;
    }

    /**
     * @return string
     */
    public function toPdf()
    {
        $this->setTemplate('Litslink_Liqpay::info/pdf/litslink.phtml');
        return $this->toHtml();
    }
}
