<?php

namespace Edd\ShopByPrice\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    protected $storeManager;
    protected $objectManager;

    const XML_PATH_SHOPBYPRICE = 'shopbyprice/';



    public function __construct(Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager
    ) {
        $this->objectManager = $objectManager;
        $this->storeManager  = $storeManager;
        parent::__construct($context);
    }

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }


    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_SHOPBYPRICE . $code, $storeId);
    }


}