<?php
/**
 * @author      Litslink
 * @category    Litslink
 * @package     Litslink_Liqpay
 * @copyright   Copyright (c) 2016
 */
namespace Litslink\Liqpay\Model;

/**
 * Class Liqpay
 *
 * @method \Magento\Quote\Api\Data\PaymentMethodExtensionInterface getExtensionAttributes()
 */
class Liqpay extends \Magento\Payment\Model\Method\AbstractMethod
{
    const PAYMENT_METHOD_LIQPAY_CODE = 'liqpay';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_LIQPAY_CODE;

    /**
     * @var string
     */
    protected $_formBlockType = 'Litslink\Liqpay\Block\Form\Liqpay';

    /**
     * @var string
     */
    protected $_infoBlockType = 'Litslink\Liqpay\Block\Info\Liqpay';

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = false;

        private $_api_url = 'https://www.liqpay.com/api/';
    private $_checkout_url = 'https://www.liqpay.com/api/3/checkout';
    protected $_supportedCurrencies = array('EUR','UAH','USD','RUB','RUR');
    private $_public_key;
    private $_private_key;



    /**
     * Call API
     *
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    public function api($path, $params = array())
    {
        if(!isset($params['version'])){
            throw new InvalidArgumentException('version is null');
        }
        
        $public_key = 'i80795659072';
        $private_key = 'FXuIZ8npblBJwS0S8ApnmF8O3pisXBtg9SMS0T7O';
        $this->_public_key = $public_key;
        $this->_private_key = $private_key;

        $url         = $this->_api_url . $path;
        $public_key  = $this->_public_key;
        $private_key = $this->_private_key;        
        $data        = base64_encode(json_encode(array_merge(compact('public_key'), $params)));
        $signature   = base64_encode(sha1($private_key.$data.$private_key, 1));
        $postfields  = http_build_query(array(
           'data'  => $data,
           'signature' => $signature
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return json_decode($server_output);
    }


    /**
     * cnb_form
     *
     * @param array $params
     *
     * @return string
     * 
     * @throws InvalidArgumentException
     */
    public function cnb_form($params)
    {        

         $language = 'ru';
        if (isset($params['language']) && $params['language'] == 'en') {
            $language = 'en';
        }

        $params    = $this->cnb_params($params);
        $data      = base64_encode( json_encode($params) );
        $signature = $this->cnb_signature($params);
        
        return sprintf('
            <form method="POST" action="%s" accept-charset="utf-8">
                %s
                %s
                <input type="image" src="//static.liqpay.com/buttons/p1%s.radius.png" name="btn_text" />
            </form>
            ',
            $this->_checkout_url,
            sprintf('<input type="hidden" name="%s" value="%s" />', 'data', $data),
            sprintf('<input type="hidden" name="%s" value="%s" />', 'signature', $signature),
            $language
        );
    }







    /**
     * cnb_signature
     *
     * @param array $params
     *
     * @return string
     */
    public function cnb_signature($params)
    {
        $params      = $this->cnb_params($params);
        $private_key = $this->_private_key;

        $json      = base64_encode( json_encode($params) );
        $signature = $this->str_to_sign($private_key . $json . $private_key);

        return $signature;
    }




    /**
     * cnb_params
     *
     * @param array $params
     *
     * @return array $params
     */
    private function cnb_params($params)
    {
        $public_key = 'i80795659072';
        $private_key = 'FXuIZ8npblBJwS0S8ApnmF8O3pisXBtg9SMS0T7O';
        $this->_public_key = $public_key;
        $this->_private_key = $private_key;
        
        $params['public_key'] = $this->_public_key;

        if (!isset($params['version'])) {
            throw new InvalidArgumentException('version is null');
        }
        if (!isset($params['amount'])) {
            throw new InvalidArgumentException('amount is null');
        }
        if (!isset($params['currency'])) {
           throw new InvalidArgumentException('currency is null');
        }
        if (!in_array($params['currency'], $this->_supportedCurrencies)) {
            throw new InvalidArgumentException('currency is not supported');
        }
        if ($params['currency'] == 'RUR') {
            $params['currency'] = 'RUB';
        }
        if (!isset($params['description'])) {
            throw new InvalidArgumentException('description is null');
        }

        return $params;
    }


    /**
     * str_to_sign
     *
     * @param string $str
     *
     * @return string
     */
    public function str_to_sign($str)
    {

        $signature = base64_encode(sha1($str,1));

        return $signature;
    } 


    /**
     * @return string
     */
    public function getPayableTo()
    {
        return $this->getConfigData('payable_to');
    }

    /**
     * @return string
     */
    public function getMailingAddress()
    {
        return $this->getConfigData('mailing_address');
    }
}

