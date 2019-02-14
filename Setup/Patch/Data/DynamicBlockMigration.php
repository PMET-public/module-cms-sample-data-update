<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace MagentoEse\CmsSampleDataUpdate\Setup\Patch\Data;


use Magento\Framework\Setup\Patch\DataPatchInterface;


class OldUpgradeData implements DataPatchInterface, PatchVersionInterface
{
    /** @var \Magento\Banner\Model\Banner  */
    private $banner;

    /** @var \Magento\Cms\Model\PageFactory  */
    private $pageFactory;

    /**
     * App State
     *
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * Old constructor.
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Banner\Model\BannerFactory $banner
     * @param \Magento\Cms\Model\PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Framework\App\State $state,
        \Magento\Banner\Model\BannerFactory $banner,
        \Magento\Cms\Model\PageFactory $pageFactory,

    ) {
        $this->banner = $banner;
        $this->pageFactory = $pageFactory;
        try{
            $state->setAreaCode('adminhtml');
        }
        catch(\Magento\Framework\Exception\LocalizedException $e){
            // left empty
        }
    }

    public function apply()
    {
        //update homepage with dynamic blocks
        $this->pageFactory->create()
            ->load('home')
            ->setContent('<div data-role="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-element="main" style="justify-content: flex-start; display: flex; flex-direction: column; background-position: left top; background-size: cover; background-repeat: no-repeat; background-attachment: scroll; border-style: none; border-width: 1px; border-radius: 0px; margin: 0px 0px 10px; padding: 10px;"><div data-role="dynamic_block" data-appearance="default" data-element="main" style="border-style: none; border-width: 1px; border-radius: 0px; margin: 0px; padding: 0px;">{{widget type="Magento\Banner\Block\Widget\Banner" display_mode="fixed" rotate="" template="widget/block.phtml" banner_ids="1" unique_id="1" type_name="Dynamic Blocks Rotator"}}</div><div data-role="dynamic_block" data-appearance="default" data-element="main" style="border-style: none; border-width: 1px; border-radius: 0px; margin: 0px; padding: 0px;">{{widget type="Magento\Banner\Block\Widget\Banner" display_mode="fixed" rotate="" template="widget/block.phtml" banner_ids="2" unique_id="2" type_name="Dynamic Blocks Rotator"}}</div><div data-role="dynamic_block" data-appearance="default" data-element="main" style="border-style: none; border-width: 1px; border-radius: 0px; margin: 0px; padding: 0px;">{{widget type="Magento\Banner\Block\Widget\Banner" display_mode="fixed" rotate="" template="widget/block.phtml" banner_ids="3" unique_id="3" type_name="Dynamic Blocks Rotator"}}</div><div data-role="dynamic_block" data-appearance="default" data-element="main" style="border-style: none; border-width: 1px; border-radius: 0px; margin: 0px; padding: 0px;">{{widget type="Magento\Banner\Block\Widget\Banner" display_mode="fixed" rotate="" template="widget/block.phtml" banner_ids="4" unique_id="4" type_name="Dynamic Blocks Rotator"}}</div></div>')
            ->save();
            
        //save block content as dynamic blocks instead of referencing blocks
        $this->banner->create()
          ->load('1')
          ->setStoreContents(file_get_contents(__DIR__ . '/../../../fixtures/luma-home-pb.txt'))
          ->save();
        $this->banner->create()
          ->load('2')
          ->setStoreContents(file_get_contents(__DIR__ . '/../../../fixtures/luma-home-vip-pb.txt'))
          ->save();
        $this->banner->create()
          ->load('3')
          ->setStoreContents(file_get_contents(__DIR__ . '/../../../fixtures/luma-home-runner-pb.txt'))
          ->save();
        $this->banner->create()
          ->load('4')
          ->setStoreContents(file_get_contents(__DIR__ . '/../../../fixtures/luma-home-yoga-pb.txt'))
          ->save();
    }

    public static function getDependencies()
    {
        return [OldUpgradeData::class];
    }

    public function getAliases()
    {
        return [];
    }

}
