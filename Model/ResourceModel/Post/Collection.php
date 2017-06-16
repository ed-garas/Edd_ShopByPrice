<?php

namespace Edd\ShopByPrice\Model\ResourceModel\Post;
use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection as AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * ID Field Name
     * 
     * @var string
     */
    protected $_idFieldName = 'post_id';

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'edd_shopbyprice_post_collection';

    /**
     * Event object
     * 
     * @var string
     */
    protected $_eventObject = 'post_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Edd\ShopByPrice\Model\Post', 'Edd\ShopByPrice\Model\ResourceModel\Post');
    }

    /**
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     */
    protected function _toOptionArray($valueField = 'post_id', $labelField = 'name', $additional = [])
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }
}
