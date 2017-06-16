<?php

namespace Edd\ShopByPrice\Controller\Adminhtml\Post;

class Delete extends \Edd\ShopByPrice\Controller\Adminhtml\Post
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->_resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('post_id');
        if ($id) {
            $name = "";
            try {
                /** @var \Edd\ShopByPrice\Model\Post $post */
                $post = $this->_postFactory->create();
                $post->load($id);
                $name = $post->getName();
                $post->delete();
                $this->messageManager->addSuccess(__('The Post has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_edd_shopbyprice_post_on_delete',
                    ['name' => $name, 'status' => 'success']
                );
                $resultRedirect->setPath('edd_shopbyprice/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_edd_shopbyprice_post_on_delete',
                    ['name' => $name, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                $resultRedirect->setPath('edd_shopbyprice/*/edit', ['post_id' => $id]);
                return $resultRedirect;
            }
        }
        // display error message
        $this->messageManager->addError(__('Block to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('edd_shopbyprice/*/');
        return $resultRedirect;
    }
}
