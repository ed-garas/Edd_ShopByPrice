<?php

namespace Edd\ShopByPrice\Controller\Adminhtml\Post;

class Edit extends \Edd\ShopByPrice\Controller\Adminhtml\Post
{
    /**
     * Backend session
     * 
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * Page factory
     * 
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Result JSON factory
     * 
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * constructor
     * 
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Edd\ShopByPrice\Model\PostFactory $postFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Edd\ShopByPrice\Model\PostFactory $postFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_backendSession    = $backendSession;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($postFactory, $registry, $resultRedirectFactory, $context);
    }

    /**
     * is action allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Edd_ShopByPrice::post');
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('post_id');
        /** @var \Edd\ShopByPrice\Model\Post $post */
        $post = $this->_initPost();
        /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Edd_ShopByPrice::post');
        $resultPage->getConfig()->getTitle()->set(__('Blocks'));
        if ($id) {
            $post->load($id);
            if (!$post->getId()) {
                $this->messageManager->addError(__('This Block no longer exists.'));
                $resultRedirect = $this->_resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'edd_shopbyprice/*/edit',
                    [
                        'post_id' => $post->getId(),
                        '_current' => true
                    ]
                );
                return $resultRedirect;
            }
        }
        $title = $post->getId() ? $post->getName() : __('New Block');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $data = $this->_backendSession->getData('edd_shopbyprice_post_data', true);
        if (!empty($data)) {
            $post->setData($data);
        }
        return $resultPage;
    }
}
