<?php

namespace Edd\ShopByPrice\Controller\Adminhtml\Post;

abstract class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * JSON Factory
     * 
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_jsonFactory;

    /**
     * Post Factory
     * 
     * @var \Edd\ShopByPrice\Model\PostFactory
     */
    protected $_postFactory;

    /**
     * constructor
     * 
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Edd\ShopByPrice\Model\PostFactory $postFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Edd\ShopByPrice\Model\PostFactory $postFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_jsonFactory = $jsonFactory;
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->_jsonFactory->create();
        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }
        foreach (array_keys($postItems) as $postId) {
            /** @var \Edd\ShopByPrice\Model\Post $post */
            $post = $this->_postFactory->create()->load($postId);
            try {
                $postData = $postItems[$postId];//todo: handle dates
                $post->addData($postData);
                $post->save();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithPostId($post, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithPostId($post, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPostId(
                    $post,
                    __('Something went wrong while saving the Post.')
                );
                $error = true;
            }
        }
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add Post id to error message
     *
     * @param \Edd\ShopByPrice\Model\Post $post
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPostId(\Edd\ShopByPrice\Model\Post $post, $errorText)
    {
        return '[Post ID: ' . $post->getId() . '] ' . $errorText;
    }
}
