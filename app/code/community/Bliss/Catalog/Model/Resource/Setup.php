<?php
/**
 * @author      James Harrison <james@blissmedia.com.au>
 */
class Bliss_Catalog_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup
{

    public function getDefaultEntities() {
        return array(
            'catalog_product'                => array(
                'entity_model'                   => 'catalog/product',
                'attribute_model'                => 'catalog/resource_eav_attribute',
                'table'                          => 'catalog/product',
                'additional_attribute_table'     => 'catalog/eav_attribute',
                'entity_attribute_collection'    => 'catalog/product_attribute_collection',
                'attributes'                     => array(
                    'bcatalog_product_badge'       => array(
                        'type'                       => 'int',
                        'label'                      => 'Product Badge',
                        'input'                      => 'select',
                        'required'                   => false,
                        'user_defined'               => true,
                        'searchable'                 => false,
                        'filterable'                 => false,
                        'comparable'                 => false,
                        'visible_in_advanced_search' => false,
                    ),
                )
            ),
            'catalog_category'               => array(
                'entity_model'                   => 'catalog/category',
                'attribute_model'                => 'catalog/resource_eav_attribute',
                'table'                          => 'catalog/category',
                'additional_attribute_table'     => 'catalog/eav_attribute',
                'entity_attribute_collection'    => 'catalog/category_attribute_collection',
                'attributes'                     => array(
                    'bcatalog_attributelink_code'        => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Linked Attribute Code',
                        'input'                      => 'text',
                        'required'                   => false,
                        'sort_order'                 => 20,
                        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'group'                      => 'General Information',
                    ),
                )
            )
        );
    }
}
