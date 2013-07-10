<?php

/**
 * Class Bliss_Catalog_Helper_Attributelink
 *
 * Used for linking attributes to categories
 * Most common use if for linking a Brands category to the manufacturer attribute
 * This helper can generate a category link based on a product and its corresponding attribute value
 *
 * @author James Harrison <james@blissmedia.com.au>
 *
 */

class Bliss_Catalog_Helper_Attributelink extends Mage_Core_Helper_Abstract
{
    /**
     * @param $product Mage_Catalog_Model_Product
     * @param $attributeCode String
     * @return bool|Varien_Object
     */
    public function getLinkedCategory($product, $attributeCode) {

        //Get the attribute value from the supplied product
        $attributeValue = $product->getAttributeText($attributeCode);
        if(!$attributeValue) return false;

        /**
         * Load the parent category based on the attributelink_code attribute
         * @var $categoryCollection Mage_Catalog_Model_Resource_Category_Collection
         * @var $parentCategory Mage_Catalog_Model_Category
         */
        $categoryCollection = Mage::getModel('catalog/category')->getCollection();
        $parentCategory = $categoryCollection->addAttributeToFilter('attributelink_code', $attributeCode)
            ->setPageSize(1)
            ->load()
            ->getFirstItem();
        if(!$parentCategory) return false;


        /**
         * Get all the child categories (Brands)
         * Search for categories that match the provided attribute value
         * @var $linkedCategories Mage_Catalog_Model_Resource_Category_Collection
         */
        $linkedCategoryIds = explode(',',$parentCategory->getChildren());
        $linkedCategories = Mage::getModel('catalog/category')->getCollection();
        $linkedCategories->addIdFilter($linkedCategoryIds)
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('name', array('eq' => $attributeValue))
            ->setPageSize(1)
            ->load();


        //Check if any categories exist, return false otherwise
        if($linkedCategories->count()) {
            return $linkedCategories->getFirstItem();
        } else {
            return false;
        }
    }

    /**
     * @param $category Mage_Catalog_Model_Category
     */
    public function getLinkedAttributeOption($category) {

    }

}