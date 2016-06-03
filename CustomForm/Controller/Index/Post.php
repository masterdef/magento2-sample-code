<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Litslink\CustomForm\Controller\Index;

class Post extends \Litslink\CustomForm\Controller\Index
{
    /**
     * Post user question
     *
     * @return void
     * @throws \Exception
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        //var_dump(908322, $post);exit;

        if (!$post) {
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
        }

        $this->inlineTranslation->suspend();
        try {
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($post);

            $error = false;

            if (!\Zend_Validate::is(trim($post['fullname']), 'NotEmpty')) {
                $error = true;
            }
            if (!\Zend_Validate::is(trim($post['email']), 'NotEmpty')) {
                $error = true;
            }
            if ($error) {
                throw new \Exception();
            }

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $this->_transportBuilder
                ->setFrom($this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
                ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
                ->setReplyTo($post['email'])
                ;

            $transport->message->setSubject('custom-form');
            $transport->message->setBody(
              var_export($post, 1)
            );

            $mailTransport = $transport->mailTransportFactory->create(['message' => clone $transport->message]);
            //$transport->getTransport();
            //var_dump(733225, $error, $post, $post['subject']);exit;

            $mailTransport->sendMessage();
            $this->inlineTranslation->resume();
            $this->messageManager->addSuccess(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
        } catch (\Exception $e) {
            //var_dump(744225, $e->getMessage());exit;
            $this->inlineTranslation->resume();
            $this->messageManager->addError(
                __('We can\'t process your request right now. Sorry, that\'s all we know.')
            );
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
        }
    }
}
