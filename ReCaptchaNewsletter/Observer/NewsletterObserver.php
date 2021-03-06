<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\ReCaptchaNewsletter\Observer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\ReCaptchaApi\Api\CaptchaConfigInterface;
use Magento\ReCaptchaUi\Model\RequestHandlerInterface;

/**
 * NewsletterObserver
 */
class NewsletterObserver implements ObserverInterface
{
    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var CaptchaConfigInterface
     */
    private $captchaConfig;

    /**
     * @var RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * @param RedirectInterface $redirect
     * @param CaptchaConfigInterface $captchaConfig
     * @param RequestHandlerInterface $requestHandler
     */
    public function __construct(
        RedirectInterface $redirect,
        CaptchaConfigInterface $captchaConfig,
        RequestHandlerInterface $requestHandler
    ) {
        $this->redirect = $redirect;
        $this->captchaConfig = $captchaConfig;
        $this->requestHandler = $requestHandler;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     */
    public function execute(Observer $observer): void
    {
        if ($this->captchaConfig->isCaptchaEnabledFor('newsletter')) {
            /** @var Action $controller */
            $controller = $observer->getControllerAction();
            $request = $controller->getRequest();
            $response = $controller->getResponse();
            $redirectOnFailureUrl = $this->redirect->getRefererUrl();

            $this->requestHandler->execute($request, $response, $redirectOnFailureUrl);
        }
    }
}
