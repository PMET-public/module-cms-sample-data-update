<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\CmsSampleDataUpdate\Model;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Cms\Api\Data\BlockInterface;

/**
 * Class Banner
 */
class Banner
{
    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csvReader;

    /**
     * @var \Magento\Framework\Setup\SampleData\FixtureManager
     */
    protected $fixtureManager;

    /**
     * @var \Magento\Banner\Model\Banner
     */
    protected $banner;

    /**
     * @var \Magento\BannerCustomerSegment\Model\ResourceModel\BannerSegmentLink
     */
    private $bannerSegmentLink;

    /** @var BlockRepositoryInterface  */
    protected $blockRepository;

    /** @var SearchCriteriaBuilder  */
    protected $searchCriteriaBuilder;

    /**
     * Banner constructor.
     * @param SampleDataContext $sampleDataContext
     * @param \Magento\Banner\Model\BannerFactory $banner
     * @param \Magento\BannerCustomerSegment\Model\ResourceModel\BannerSegmentLink $bannerSegmentLink
     * @param BlockRepositoryInterface $blockRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        \Magento\Banner\Model\BannerFactory $banner,
        \Magento\BannerCustomerSegment\Model\ResourceModel\BannerSegmentLink $bannerSegmentLink,
        BlockRepositoryInterface $blockRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->csvReader = $sampleDataContext->getCsvReader();
        $this->banner = $banner;
        $this->bannerSegmentLink = $bannerSegmentLink;
        $this->blockRepository = $blockRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function install(array $fixtures)
    {
        foreach ($fixtures as $fileName) {
            $fileName = $this->fixtureManager->getFixture($fileName);
            if (!file_exists($fileName)) {
                throw new Exception('File not found: '.$fileName);
            }

            $rows = $this->csvReader->getData($fileName);
            $header = array_shift($rows);

            foreach ($rows as $row) {
                $data = [];
                foreach ($row as $key => $value) {
                    $data[$header[$key]] = $value;
                }
                $row = $data;

                $banner = $this->banner->create();
                $banner->setName($row['name']);
                $banner->setIsEnabled(1);
                $banner->setTypes('content');
                $content = $this->replaceBlockIdentifiers($row['banner_content']);
                $banner->setStoreContents(array($row['store_id'],$content));
                $banner->save();
            }
        }
        //assign segments to banners
        $this->bannerSegmentLink->saveBannerSegments(1,[1]);
        $this->bannerSegmentLink->saveBannerSegments(2,[2]);
        $this->bannerSegmentLink->saveBannerSegments(3,[3]);
        $this->bannerSegmentLink->saveBannerSegments(4,[4]);
    }

    private function replaceBlockIdentifiers($blockContent){
        $search = $this->searchCriteriaBuilder
            ->addFilter(BlockInterface::IDENTIFIER,'','neq')->create();
        $blocklist = $this->blockRepository->getList($search)->getItems();
        foreach($blocklist as $block){
            $identifier = $block->getIdentifier();
            $blockId = $block->getId();
            $blockContent = str_replace($identifier,$blockId,$blockContent);
        }
        return $blockContent;
    }
}
