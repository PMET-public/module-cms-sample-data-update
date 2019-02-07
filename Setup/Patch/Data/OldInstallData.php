<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagentoEse\CmsSampleDataUpdate\Setup\Patch\Data;


use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use MagentoEse\CmsSampleDataUpdate\Model\Block;


class OldInstallData implements DataPatchInterface, PatchVersionInterface
{


    /** @var Block  */
    protected $block;


    /**
     * OldUpgradeData constructor.
     * @param Block $block
     */
    public function __construct(
        Block $block
    ) {
        $this->block = $block;
    }

    public function apply()
    {
        $this->block->install(
            [
                'MagentoEse_CmsSampleDataUpdate::fixtures/blocks/categories_static_blocks.csv',
                'MagentoEse_CmsSampleDataUpdate::fixtures/blocks/categories_static_blocks_giftcard.csv',
                'MagentoEse_CmsSampleDataUpdate::fixtures/blocks/pages_static_blocks.csv',
            ]
        );
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public static function getVersion()
    {
        return '0.0.3';
    }

}