<?php

namespace Edd\ShopByPrice\Model;
use \Magento\Framework\DataObject\IdentityInterface as IdentityInterface;
use \Edd\ShopByPrice\Model\PostInterface as PostInterface;


class Post extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     * 
     * @var string
     */
    const CACHE_TAG = 'edd_shopbyprice_post';

    /**
     * Cache tag
     * 
     * @var string
     */
    protected $_cacheTag = 'edd_shopbyprice_post';

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'edd_shopbyprice_post';


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Edd\ShopByPrice\Model\ResourceModel\Post');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * get entity default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
