<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\NotifierAdminUi\Ui\Component\Listing\Channel;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Ui\Component\Control\Container;
use Magento\NotifierApi\Api\AdaptersPoolInterface;
use Magento\NotifierApi\Api\Data\ChannelInterface;

class NewButton extends Generic
{
    /**
     * @var AdaptersPoolInterface
     */
    private $adapterRepository;

    /**
     * NewButton constructor.
     * @param Context $context
     * @param Registry $registry
     * @param AdaptersPoolInterface $adapterRepository
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AdaptersPoolInterface $adapterRepository
    ) {
        parent::__construct($context, $registry);
        $this->adapterRepository = $adapterRepository;
    }

    /**
     * @inheritdoc
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('New channel'),
            'class_name' => Container::SPLIT_BUTTON,
            'options' => $this->getOptions(),
            'sort_order' => 10
        ];
    }

    /**
     * Get adapters list
     * @return array
     */
    private function getOptions(): array
    {
        $adapters = $this->adapterRepository->getAdapters();

        $options = [];
        foreach ($adapters as $adapter) {
            $options[] = [
                'id_hard' => 'adapter_' . $adapter->getCode(),
                'label' => $adapter->getDescription(),
                'onclick' => sprintf("location.href = '%s';", $this->getUrl('magento_notifier/channel/new', [
                    'adapter_code' => $adapter->getCode(),
                ])),
            ];
        }

        return $options;
    }
}
