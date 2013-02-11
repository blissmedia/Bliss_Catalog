<?php
/**
 * Cart crosssell list with category override option
 *
 * @category   Bliss
 * @package    Bliss_Crosssellcategory
 * @author     James Harrison <james@blissmedia.com.au>
 */
class Bliss_Crosssellcategory_Block_Cart_Crosssell extends Mage_Checkout_Block_Cart_Crosssell
{

    const XML_PATH_CROSSSELL_USE_CATEGORY        = 'checkout/cart/crosssell_use_category';
    const XML_PATH_CROSSSELL_CATEGORY_ID         = 'checkout/cart/crosssell_category_id';

    /**
     * Get crosssell items
     *
     * @return array
     */
    public function getItems()
    {
        /** @var $category Mage_Catalog_Model_Category */
        $categoryId = $this->_getOverrideCategoryId();
        $category = Mage::getModel('catalog/category');

        //return normal cross-sell collection if this feature is turned off or category doesn't exist.
        if(!Mage::getStoreConfig(self::XML_PATH_CROSSSELL_USE_CATEGORY)
            || !$category->checkId($categoryId)) return parent::getItems();

        $items = $this->getData('items');
        if (is_null($items)) {
            $items = array();
            $ninProductIds = $this->_getCartProductIds();
            if ($ninProductIds) {
                $lastAdded = (int) $this->_getLastAddedProductId();
                if ($lastAdded) {
                    $collection = $this->_getOverrideCollection()
                        ->addIdFilter($lastAdded, true);
                    if (!empty($ninProductIds)) {
                        $collection->addIdFilter($ninProductIds, true);
                    }
                    $collection->load();

                    foreach ($collection as $item) {
                        $ninProductIds[] = $item->getId();
                        $items[] = $item;
                    }
                }

                if (count($items) < $this->_maxItemCount) {
                    $filterProductIds = array_merge($this->_getCartProductIds(), $this->_getCartProductIdsRel());
                    $collection = $this->_getOverrideCollection()
                        ->addIdFilter($filterProductIds,true)
                        ->setPageSize($this->_maxItemCount-count($items))
                        ->load();
                    foreach ($collection as $item) {
                        $items[] = $item;
                    }
                }

            }

            $this->setData('items', $items);
        }
        return $items;
    }

    /**
     * Get crosssell products collection
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _getOverrideCollection()
    {
        /** @var $category Mage_Catalog_Model_Category */
        $categoryId = $this->_getOverrideCategoryId();
        $category = Mage::getModel('catalog/category');

        $category->load($categoryId);
        /** @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = $category->getProductCollection()
            ->setStoreId(Mage::app()->getStore()->getId())
            ->addStoreFilter()
            ->setPageSize($this->_maxItemCount);
        $this->_addProductAttributesAndPrices($collection);

        Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);

        return $collection;
    }


    protected function _getOverrideCategoryId() {
        return Mage::getStoreConfig(self::XML_PATH_CROSSSELL_CATEGORY_ID);
    }
}
