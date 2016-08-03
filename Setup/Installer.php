<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\CmsSampleDataUpdate\Setup;

use Magento\Framework\Setup;

class Installer implements Setup\SampleData\InstallerInterface
{
    /**
     * @var \Magento\CmsSampleData\Model\Page
     */
    private $page;

    /**
     * @var \Magento\CmsSampleData\Model\Block
     */
    private $block;

    /**
     * @param \Magento\CmsSampleData\Model\Page $page
     * @param \Magento\CmsSampleData\Model\Block $block
     */
    public function __construct(
        \MagentoEse\CmsSampleDataUpdate\Model\Block $block
    ) {
        $this->page = $page;
        $this->block = $block;
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        $this->block->install(
            [
                'MagentoEse_CmsSampleDataUpdate::fixtures/blocks/categories_static_blocks.csv',
                'MagentoEse_CmsSampleDataUpdate::fixtures/blocks/categories_static_blocks_giftcard.csv',
                'MagentoEse_CmsSampleDataUpdate::fixtures/blocks/pages_static_blocks.csv',
            ]
        );
    }
}
