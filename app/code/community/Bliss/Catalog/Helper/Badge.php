<?php

class Bliss_Catalog_Helper_Badge extends Mage_Core_Helper_Abstract
{
    /**
     * Method for getting a given product's badge (new, sale, featured, etc)
     * Only 1 badge is ever returned, so the order in which they are presented here determines which badge takes priority
     *
     * Priorities:
     *   1. New
     *   2. Sale
     *   3. Custom
     *
     * TODO: Add functionality to set priorities in config
     *
     * @param $product Mage_Catalog_Model_Product
     * @return array
     */
    public function getProductBadge($product) {

        $badge = array();

        if(Mage::getStoreConfig('catalog/frontend/override_product_badge')) {

            $todayStartOfDayDate  = Mage::app()->getLocale()->date()
                ->setTime('00:00:00')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

            $todayEndOfDayDate  = Mage::app()->getLocale()->date()
                ->setTime('23:59:59')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);


            /*
             * New Badge
             * Must have valid news_from_date
             * Optional news_to_date
             */
            if($product->hasData('news_from_date') && $product->getData('news_from_date') != null) {

                if(($product->getData('news_from_date') < $todayEndOfDayDate)
                    && ($product->getData('news_to_date') > $todayStartOfDayDate || $product->getData('news_to_date') == null))
                {
                    $badge['code']  = 'new';
                    $badge['label'] = 'New';
                    return $badge;
                }
            }

            /*
             * Sale Badge
             * Must have special_price
             * Must have valid special_from_date
             */
            if($product->getSpecialPrice() > 0 && $product->getSpecialPrice() < $product->getPrice()) {

                if(($product->getData('special_from_date') < $todayEndOfDayDate)
                    && ($product->getData('special_to_date') > $todayStartOfDayDate || $product->getData('special_to_date') == null)
                    && $product->getData('special_price') != null)
                {
                    $badge['code']  = 'sale';
                    $badge['label'] = 'Sale';
                    return $badge;
                }
            }
        }

        /*
         * Custom value
         * More values can be added via bcatalog_badge attribute
         */
        if($badgeLabel = $product->getAttributeText('bcatalog_badge')) {
            $badge['code']  = strtolower(str_replace(' ', '_', $badgeLabel));
            $badge['label'] = $badgeLabel;
            return $badge;
        }

    }

}