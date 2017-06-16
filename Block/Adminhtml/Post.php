<?php
namespace Edd\ShopByPrice\Block\Adminhtml;

class Post extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_post';
        $this->_blockGroup = 'Edd_ShopByPrice';
        $this->_headerText = __('Shop by Price Block List');
        $this->_addButtonLabel = __('Create New Block');
        parent::_construct();
    }
}
