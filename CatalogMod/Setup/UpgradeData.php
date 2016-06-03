<?php

/**
 * @author      Litslink
 * @category    Litslink
 * @package     Litslink_CatalogMod
 * @copyright   Copyright (c) 2016
 */
namespace Litslink\CatalogMod\Setup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
 
/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    
    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    private $eavSetupFactory;
    
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;
    
    /**
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavSetupFactory = $eavSetupFactory;
    }
 
    
    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '1.0.3', '<')) 
        {
          /**
           * Install eav entity types to the eav/entity_type table
           */
          $eavSetup->addAttribute(
              'catalog_product',
              'description_gallery_2',
              [
                  'type' => 'varchar',
                  'label' => 'Description Gallery 2',
                  'input' => 'media_image',
                  'frontend' => 'Magento\Catalog\Model\Product\Attribute\Frontend\Image',
                  'required' => false,
                  'sort_order' => 7,
                  'global' => 
                    \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                  'used_in_product_listing' => true
              ]
          );

          /**
           * Install eav entity types to the eav/entity_type table
           */
          $eavSetup->addAttribute(
              'catalog_product',
              'description_gallery_3',
              [
                  'type' => 'varchar',
                  'label' => 'Description Gallery 3',
                  'input' => 'media_image',
                  'frontend' => 'Magento\Catalog\Model\Product\Attribute\Frontend\Image',
                  'required' => false,
                  'sort_order' => 7,
                  'global' => 
                    \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                  'used_in_product_listing' => true
              ]
          );
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) 
        {
          /**
           * Install eav entity types to the eav/entity_type table
           */
          $eavSetup->addAttribute(
              'catalog_product',
              'description_gallery_1',
              [
                  'type' => 'varchar',
                  'label' => 'Description Gallery 1',
                  'input' => 'media_image',
                  'frontend' => 'Magento\Catalog\Model\Product\Attribute\Frontend\Image',
                  'required' => false,
                  'sort_order' => 7,
                  'global' => 
                    \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                  'used_in_product_listing' => true
              ]
          );
        }
    }
} //endclass

