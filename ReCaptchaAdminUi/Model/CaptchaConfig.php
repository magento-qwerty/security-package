<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\ReCaptchaAdminUi\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Phrase;
use Magento\ReCaptchaApi\Api\CaptchaConfigInterface;

/**
 * @inheritdoc
 */
class CaptchaConfig implements CaptchaConfigInterface
{
    private const XML_PATH_TYPE = 'recaptcha/backend/type';
    private const XML_PATH_PUBLIC_KEY = 'recaptcha/backend/public_key';
    private const XML_PATH_PRIVATE_KEY = 'recaptcha/backend/private_key';

    private const XML_PATH_SCORE_THRESHOLD = 'recaptcha/backend/score_threshold';
    private const XML_PATH_SIZE = 'recaptcha/backend/size';
    private const XML_PATH_THEME = 'recaptcha/backend/theme';
    private const XML_PATH_POSITION = 'recaptcha/frontend/position';
    private const XML_PATH_LANGUAGE_CODE = 'recaptcha/frontend/lang';

    private const XML_PATH_IS_ENABLED_FOR = 'recaptcha/backend/enabled_for_';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function getPublicKey(): string
    {
        return trim((string)$this->scopeConfig->getValue(self::XML_PATH_PUBLIC_KEY));
    }

    /**
     * @inheritdoc
     */
    public function getPrivateKey(): string
    {
        return trim((string)$this->scopeConfig->getValue(self::XML_PATH_PRIVATE_KEY));
    }

    /**
     * @inheritdoc
     */
    public function getCaptchaType(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_TYPE);
    }

    /**
     * @inheritdoc
     */
    public function getSize(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_SIZE);
    }

    /**
     * @inheritdoc
     */
    public function getTheme(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_THEME);
    }

    /**
     * @inheritdoc
     */
    public function getScoreThreshold(): float
    {
        return min(1.0, max(0.1, (float) $this->scopeConfig->getValue(
            self::XML_PATH_SCORE_THRESHOLD
        )));
    }

    /**
     * @inheritdoc
     */
    public function getErrorMessage(): Phrase
    {
        if ($this->getCaptchaType() === 'recaptcha_v3') {
            return __('You cannot proceed with such operation, your reCAPTCHA reputation is too low.');
        }

        return __('Incorrect reCAPTCHA validation.');
    }

    /**
     * @inheritdoc
     */
    public function getLanguageCode(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_LANGUAGE_CODE
        );
    }

    /**
     * @inheritdoc
     */
    public function getInvisibleBadgePosition(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_POSITION
        );
    }

    /**
     * @inheritdoc
     */
    public function isCaptchaEnabledFor(string $key): bool
    {
        if (!$this->areKeysConfigured()) {
            return false;
        }

        $flag = self::XML_PATH_IS_ENABLED_FOR . $key;
        return $this->scopeConfig->isSetFlag($flag);
    }

    /**
     * Return true if reCAPTCHA keys (public and private) are configured
     *
     * @return bool
     */
    private function areKeysConfigured(): bool
    {
        return $this->getPrivateKey() && $this->getPublicKey();
    }
}
