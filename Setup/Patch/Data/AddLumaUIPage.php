<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagentoEse\CmsSampleDataUpdate\Setup\Patch\Data;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use MagentoEse\CmsSampleDataUpdate\Model\Page;

class AddLumaUiPage implements DataPatchInterface
{

    /** @var Page  */
    private $page;

    /** @var PageRepositoryInterface  */
    private $pageRepository;

    /**
     * AddLumaUiPage constructor.
     * @param Page $page
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(Page $page, PageRepositoryInterface $pageRepository)
    {
        $this->page = $page;
        $this->pageRepository = $pageRepository;
    }

    public function apply(){
        $this->page->install(['MagentoEse_CmsSampleDataUpdate::fixtures/luma_ui_page.csv']);
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }


}