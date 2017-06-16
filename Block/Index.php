<?php

namespace Edd\ShopByPrice\Block;

use Magento\Framework\View\Element\Template as Template;
use Magento\Framework\View\Element\Template\Context as Context;
use Edd\ShopByPrice\Model\PostFactory as PostFactory;
use Magento\Framework\Filesystem\DirectoryList as DirectoryList;

class Index extends Template
{

    protected $_postFactory;
    protected $_postCollection;
    protected $_urlBuilder;
    protected $_storeManager;
    protected $_dir;
    public static $moduleMedia = 'edd/shopbyprice/post/image';


    public function __construct(
        Context $context,
        PostFactory $postFactory,
        DirectoryList $dir,
        array $data = []
    )
    {
        $this->_postFactory = $postFactory;
        $this->_urlBuilder = $context->getUrlBuilder();
        $this->_storeManager = $context->getStoreManager();
        $this->_dir = $dir;
        parent::__construct(
            $context,
            $data
        );
    }


    public function getMediaDir()
    {
        return $this->_dir->getPath('media');
    }

    public function isImageExist($imgUrl){
        $mediaDir = $this->getMediaDir() . DIRECTORY_SEPARATOR . self::$moduleMedia;
        $file = $mediaDir.$imgUrl;
        return $file;
    }

    public function getMediaUrl($image)
    {
        if($image == '' || $image == null){
            return '';
        }

        if(!file_exists($this->isImageExist($image))){
            return '';
        }

        $url = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $full_url = $url . self::$moduleMedia . $image;
        return $full_url;
    }

    public function getPostItems()
    {
        if (null === $this->_postCollection) {
            $post = $this->_postFactory->create();
            $collection = $post->getCollection();
            $this->_postCollection = $collection->getData();
        }
        return $this->_postCollection;
    }

    public function getProductsUrl($price_min, $price_max)
    {
        $price_min = $price_min ? $price_min : '0';
        if($price_max == '' || $price_max == '0'){
            $price_max = '';
        }
        return $this->_storeManager->getStore()->getUrl('products?price=' . $price_min . '-' . $price_max);
    }

}