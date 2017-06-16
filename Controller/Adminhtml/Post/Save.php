<?php

namespace Edd\ShopByPrice\Controller\Adminhtml\Post;

class Save extends \Edd\ShopByPrice\Controller\Adminhtml\Post
{
    /**
     * Upload model
     * 
     * @var \Edd\ShopByPrice\Model\Upload
     */
    protected $_uploadModel;

    /**
     * File model
     * 
     * @var \Edd\ShopByPrice\Model\Post\File
     */
    protected $_fileModel;

    /**
     * Image model
     * 
     * @var \Edd\ShopByPrice\Model\Post\Image
     */
    protected $_imageModel;

    /**
     * Backend session
     * 
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * constructor
     * 
     * @param \Edd\ShopByPrice\Model\Upload $uploadModel
     * @param \Edd\ShopByPrice\Model\Post\File $fileModel
     * @param \Edd\ShopByPrice\Model\Post\Image $imageModel
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Edd\ShopByPrice\Model\PostFactory $postFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Edd\ShopByPrice\Model\Upload $uploadModel,
        \Edd\ShopByPrice\Model\Post\File $fileModel,
        \Edd\ShopByPrice\Model\Post\Image $imageModel,
        \Magento\Backend\Model\Session $backendSession,
        \Edd\ShopByPrice\Model\PostFactory $postFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_uploadModel    = $uploadModel;
        $this->_fileModel      = $fileModel;
        $this->_imageModel     = $imageModel;
        $this->_backendSession = $backendSession;
        parent::__construct($postFactory, $registry, $resultRedirectFactory, $context);
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('post');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->_filterData($data);
            $post = $this->_initPost();
            $post->setData($data);
            $featuredImage = $this->_uploadModel->uploadFileAndGetName('featured_image', $this->_imageModel->getBaseDir(), $data);
            $post->setFeaturedImage($featuredImage);
            $sampleUploadFile = $this->_uploadModel->uploadFileAndGetName('sample_upload_file', $this->_fileModel->getBaseDir(), $data);
            $post->setSampleUploadFile($sampleUploadFile);
            $this->_eventManager->dispatch(
                'edd_shopbyprice_post_prepare_save',
                [
                    'post' => $post,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $post->save();
                $this->messageManager->addSuccess(__('The Block has been saved.'));
                $this->_backendSession->setEddShopByPricePostData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'edd_shopbyprice/*/edit',
                        [
                            'post_id' => $post->getId(),
                            '_current' => true
                        ]
                    );
                    return $resultRedirect;
                }
                $resultRedirect->setPath('edd_shopbyprice/*/');
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Post.'));
            }
            $this->_getSession()->setEddShopByPricePostData($data);
            $resultRedirect->setPath(
                'edd_shopbyprice/*/edit',
                [
                    'post_id' => $post->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('edd_shopbyprice/*/');
        return $resultRedirect;
    }

    /**
     * filter values
     *
     * @param array $data
     * @return array
     */
    protected function _filterData($data)
    {
        if (isset($data['sample_multiselect'])) {
            if (is_array($data['sample_multiselect'])) {
                $data['sample_multiselect'] = implode(',', $data['sample_multiselect']);
            }
        }
        return $data;
    }
}
