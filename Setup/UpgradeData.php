<?php
/**
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\CmsSampleDataUpdate\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
    private $block;
    private $segment;
    private $banner;

    /**
     * App State
     *
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @param \MagentoEse\CmsSampleDataUpdate\Model\Block $block
     * @param \MagentoEse\CmsSampleDataUpdate\Model\Segment $segment
     * @param \MagentoEse\CmsSampleDataUpdate\Model\Banner $banner
     */
    public function __construct(
        \Magento\Framework\App\State $state,
        \MagentoEse\CmsSampleDataUpdate\Model\Block $block,
        \MagentoEse\CmsSampleDataUpdate\Model\Segment $segment,
        \MagentoEse\CmsSampleDataUpdate\Model\Banner $banner,
        \Magento\Cms\Model\PageFactory $pageFactory

    ) {
        $this->block = $block;
        $this->segment = $segment;
        $this->banner = $banner;
        $this->pageFactory = $pageFactory;
        try{
            $state->setAreaCode('adminhtml');
        }
        catch(\Magento\Framework\Exception\LocalizedException $e){
            // left empty
        }
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '0.0.2') < 0
        ) {
          //add new homepage blocks
          $this->block->install(['MagentoEse_CmsSampleDataUpdate::fixtures/blocks/persona_home_blocks.csv']);

          //add segments
          $this->segment->install(['MagentoEse_CmsSampleDataUpdate::fixtures/segments.csv']);

          //add banners
          $this->banner->install(['MagentoEse_CmsSampleDataUpdate::fixtures/banners.csv']);

          /**
           * @todo Delete Homepage widget
           * admin/widget_instance/delete/instance_id/14/
           */
           $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()
           ->get('Magento\Framework\App\ResourceConnection');
           $connection= $this->_resources->getConnection();

           $widgetInstanceTable = $this->_resources->getTableName('widget_instance');
           $sql = "DELETE FROM " . $widgetInstanceTable . " WHERE 'instance_id' = 14";
           $connection->query($sql);

          //update homepage with banners
          $this->pageFactory->create()
              ->load('home')
              ->setContent('<p>{{widget type="Magento\Banner\Block\Widget\Banner" display_mode="fixed" types="content" rotate="" banner_ids="1,2,3,4" template="widget/block.phtml" unique_id="f91bb74da701824f52d10d816238cdf7"}}</p>
<p>{{widget type="Magento\CatalogWidget\Block\Product\ProductsList" show_pager="0" products_count="5" template="Magento_CatalogWidget::product/widget/content/grid.phtml" conditions_encoded="^[`1`:^[`type`:`Magento||CatalogWidget||Model||Rule||Condition||Combine`,`aggregator`:`all`,`value`:`1`,`new_child`:``^],`1--1`:^[`type`:`Magento||CatalogWidget||Model||Rule||Condition||Product`,`attribute`:`badge`,`operator`:`^[^]`,`value`:[`7`]^]^]"}}</p>')
              ->save();
        }
        $setup->endSetup();
    }
}
