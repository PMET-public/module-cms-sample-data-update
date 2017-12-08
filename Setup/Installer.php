<?php
/**
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MagentoEse\CmsSampleDataUpdate\Setup;

use Magento\Framework\Setup;

class Installer implements Setup\SampleData\InstallerInterface
{

  /**
   * @var \MagentoEse\CmsSampleDataUpdate\Model\Bluefoot
   */
  private $bluefoot;

  /**
   * Install data constructor.
   * @param \Magento\CmsSampleDataUpdate\Model\Bluefoot $bluefoot
   */
   public function __construct(
       \MagentoEse\CmsSampleDataUpdate\Model\Bluefoot $bluefoot
   ) {
       $this->bluefoot = $bluefoot;
   }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
      // Homepage Block CMS
      $this->bluefoot->updateCmsBlockContent('home-page-block', $this->bluefoot->buildStructureFromTemplate(__DIR__ . '/../fixtures/bluefoot/luma-home.json'));

      // CLP Woman Block CMS
      //this->bluefoot->updateCmsBlockContent('woman-block', $this->bluefoot->buildStructureFromTemplate(__DIR__ . '/../fixtures/bluefoot/luma-woman-block.json'));

      // CLP Training Block CMS
      //$this->bluefoot->updateCmsBlockContent('training-block', $this->bluefoot->buildStructureFromTemplate(__DIR__ . '/../fixtures/bluefoot/luma-training-block.json'));

      // CLP Men Block CMS
      //$this->bluefoot->updateCmsBlockContent('men-block', $this->bluefoot->buildStructureFromTemplate(__DIR__ . '/../fixtures/bluefoot/luma-men-block.json'));

      // CLP Gear Block CMS
      //$this->bluefoot->updateCmsBlockContent('gear-block', $this->bluefoot->buildStructureFromTemplate(__DIR__ . '/../fixtures/bluefoot/luma-gear-block.json'));

      // CLP Sale Block CMS
      //$this->bluefoot->updateCmsBlockContent('sale-block', $this->bluefoot->buildStructureFromTemplate(__DIR__ . '/../fixtures/bluefoot/luma-sale-block.json'));

      // CLP New Block CMS
      //$this->bluefoot->updateCmsBlockContent('new-block', $this->bluefoot->buildStructureFromTemplate(__DIR__ . '/../fixtures/bluefoot/luma-new-block.json'));

      // CLP GiftCard Block CMS
      //$this->bluefoot->updateCmsBlockContent('giftcard-block', $this->bluefoot->buildStructureFromTemplate(__DIR__ . '/../fixtures/bluefoot/luma-giftcard-block.json'));

      // Homepage CMS template
      $this->bluefoot->saveBluefootTemplate('Luma Home', json_encode(json_decode(file_get_contents(__DIR__ . '/../fixtures/bluefoot/luma-home.json'),true)), file_get_contents(__DIR__ . '/../fixtures/bluefoot/luma-home.png.txt'));

      // CLP Block template
      //$this->bluefoot->saveBluefootTemplate('Luma CLP', json_encode(json_decode(file_get_contents(__DIR__ . '/../fixtures/bluefoot/luma-woman-block.json'),true)), file_get_contents(['MagentoEse_CmsSampleDataUpdate::fixtures/bluefoot/luma-woman-block.png.txt']));
    }
}
