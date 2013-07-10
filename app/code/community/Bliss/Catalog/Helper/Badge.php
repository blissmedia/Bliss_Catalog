<?php

/**
 * Class Bliss_Catalog_Helper_Badge
 * 
 * TODO: create SQL install script that adds product_badge attribute
 * 
 */

class Bliss_Catalog_Helper_Badge extends Mage_Core_Helper_Abstract
{
    /**
     * Method for getting a given product's badge (new, sale, featured, etc)
     * Only 1 badge is ever returned, so the order in which they are presented here determines which badge takes priority
     *
     * TODO: Add functionality to set priorities
     *
     * @param $product Mage_Catalog_Model_Product
     * @return array
     */
    public function getProductBadge($product) {

        $badge = array();

        //TODO: get any badges set on product via attribute


        $todayStartOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate  = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        //new badge
        if($product->hasData('news_from_date') && $product->hasData('news_to_date')) {

            if(($product->getData('news_from_date') < $todayEndOfDayDate || $product->getData('news_from_date') == null)
                && ($product->getData('news_to_date') > $todayStartOfDayDate || $product->getData('news_to_date') == null))
            {
                $badge['code'] = 'new';
                $badge['name'] = 'New';
                return $badge;
            }
        }

        //sale badge
        if($product->hasData('special_from_date') && $product->hasData('special_to_date')) {

            if(($product->getData('special_from_date') < $todayEndOfDayDate || $product->getData('special_from_date') == null)
                && ($product->getData('special_to_date') > $todayStartOfDayDate || $product->getData('special_to_date') == null)
                && $product->getData('special_price') != null)
            {
                $badge['code'] = 'sale';
                $badge['name'] = 'Sale';
                return $badge;
            }
        }
    }

}