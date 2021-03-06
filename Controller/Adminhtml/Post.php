<?php

namespace Edd\ShopByPrice\Controller\Adminhtml;

abstract class Post extends \Magento\Backend\App\Action
{
    /**
     * Post Factory
     * 
     * @var \Edd\ShopByPrice\Model\PostFactory
     */
    protected $_postFactory;

    /**
     * Core registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Result redirect factory
     * 
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    protected $_resultRedirectFactory;

    /**
     * constructor
     * 
     * @param \Edd\ShopByPrice\Model\PostFactory $postFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Edd\ShopByPrice\Model\PostFactory $postFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_postFactory           = $postFactory;
        $this->_coreRegistry          = $coreRegistry;
        $this->_resultRedirectFactory = $resultRedirectFactory;
        parent::__construct($context);
    }

    /**
     * Init Post
     *
     * @return \Edd\ShopByPrice\Model\Post
     */
    protected function _initPost()
    {
        $postId  = (int) $this->getRequest()->getParam('post_id');
        /** @var \Edd\ShopByPrice\Model\Post $post */
        $post    = $this->_postFactory->create();
        if ($postId) {
            $post->load($postId);
        }
        $this->_coreRegistry->register('edd_shopbyprice_post', $post);
        return $post;
    }
}
