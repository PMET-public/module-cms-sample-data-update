<?php
/**
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\CmsSampleDataUpdate\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Widget\Controller\Adminhtml\Widget;

class UpgradeData implements UpgradeDataInterface
{
    private $block;
    private $segment;
    private $banner;
    private $widgetFactory;

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
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Widget\Model\Widget\InstanceFactory $widgetFactory

    ) {
        $this->block = $block;
        $this->segment = $segment;
        $this->banner = $banner;
        $this->pageFactory = $pageFactory;
        $this->blockFactory = $blockFactory;
        $this->widgetFactory = $widgetFactory;
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

          //delete homepage widget
          $widgetInstance = $this->widgetFactory->create();
          $instanceId = 14; //this is the default ID from SampleData
          $widgetInstance->load($instanceId)->delete();

          //update homepage with banners
          $this->pageFactory->create()
              ->load('home')
              ->setContent('<p>{{widget type="Magento\Banner\Block\Widget\Banner" display_mode="fixed" types="content" rotate="" banner_ids="1,2,3,4" template="widget/block.phtml" unique_id="f91bb74da701824f52d10d816238cdf7"}}</p>
<p>{{widget type="Magento\CatalogWidget\Block\Product\ProductsList" show_pager="0" products_count="5" template="Magento_CatalogWidget::product/widget/content/grid.phtml" conditions_encoded="^[`1`:^[`type`:`Magento||CatalogWidget||Model||Rule||Condition||Combine`,`aggregator`:`all`,`value`:`1`,`new_child`:``^],`1--1`:^[`type`:`Magento||CatalogWidget||Model||Rule||Condition||Product`,`attribute`:`badge`,`operator`:`^[^]`,`value`:[`5`]^]^]"}}</p>')
              ->save();

          //update homepage block to remove product widget
          $this->blockFactory->create()
              ->load('home-page-block')
              ->setContent('<div class="blocks-promo">
   <a href="{{store url=""}}collections/yoga-new.html" class="block-promo home-main">
       <img src="{{media url="wysiwyg/home/home-main.jpg"}}" alt="" />
       <span class="content bg-white">
           <span class="info">New Luma Yoga Collection</span>
           <strong class="title">Get fit and look fab in new seasonal styles</strong>
           <span class="action more button">Shop New Yoga</span>
       </span>
   </a>
   <div class="block-promo-wrapper block-promo-hp">
       <a href="{{store url=""}}promotions/pants-all.html" class="block-promo home-pants">
           <img src="{{media url="wysiwyg/home/home-pants.jpg"}}" alt="" />
           <span class="content">
               <strong class="title">20% OFF</strong>
               <span class="info">Luma pants when you shop today*</span>
               <span class="action more icon">Shop Pants</span>
           </span>
       </a>
       <a href="{{store url=""}}promotions/tees-all.html" class="block-promo home-t-shirts">
           <span class="image"><img src="{{media url="wysiwyg/home/home-t-shirts.png"}}" alt="" /></span>
           <span class="content">
               <strong class="title">Even more ways to mix and match</strong>
               <span class="info">Buy 3 Luma tees get a 4th free</span>
               <span class="action more icon">Shop Tees</span>
           </span>
       </a>
       <a href="{{store url=""}}collections/erin-recommends.html" class="block-promo home-erin">
           <img src="{{media url="wysiwyg/home/home-erin.jpg"}}" alt="" />
           <span class="content">
               <strong class="title">Take it from Erin</strong>
               <span class="info">Luma founder Erin Renny shares her favorites!</span>
               <span class="action more icon">Shop Erin Recommends</span>
           </span>
       </a>
       <a href="{{store url=""}}collections/performance-fabrics.html" class="block-promo home-performance">
           <img src="{{media url="wysiwyg/home/home-performance.jpg"}}" alt="" />
           <span class="content bg-white">
               <strong class="title">Science meets performance</strong>
               <span class="info">Wicking to raingear, Luma covers&nbsp;you</span>
               <span class="action more icon">Shop Performance</span>
           </span>
       </a>
       <a href="{{store url=""}}collections/eco-friendly.html" class="block-promo home-eco">
           <img src="{{media url="wysiwyg/home/home-eco.jpg"}}" alt="" />
           <span class="content bg-white">
               <strong class="title">Twice around, twice as nice</strong>
               <span class="info">Find conscientious, comfy clothing in our <nobr>eco-friendly</nobr> collection</span>
               <span class="action more icon">Shop Eco-Friendly</span>
           </span>
       </a>
   </div>
</div>
<div class="content-heading">
   <h2 class="title">Hot Sellers</h2>
   <p class="info">Here is what`s trending on Luma right now</p>
</div>')
              ->save();
        }
        if (version_compare($context->getVersion(), '0.0.3') < 0
        ) {
          //update pages with PB content
          $this->blockFactory->create()
              ->load('home-page-block')
              ->setContent(file_get_contents('MagentoEse_CmsSampleDataUpdate::fixtures/luma-home-pb.txt'))
              ->save();
          $this->blockFactory->create()
              ->load('home-page-vip')
              ->setContent(file_get_contents('MagentoEse_CmsSampleDataUpdate::fixtures/luma-home-vip-pb.txt'))
              ->save();
          $this->blockFactory->create()
              ->load('home-page-runner')
              ->setContent(file_get_contents('MagentoEse_CmsSampleDataUpdate::fixtures/luma-home-runner-pb.txt'))
              ->save();
          $this->blockFactory->create()
              ->load('home-page-yoga')
              ->setContent(file_get_contents('MagentoEse_CmsSampleDataUpdate::fixtures/luma-home-yoga-pb.txt'))
              ->save();
        }
        $setup->endSetup();
    }
}
