<?php

namespace Edd\ShopByPrice\Block\Adminhtml\Post\Edit\Tab;

class Post extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Wysiwyg config
     * 
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * Country options
     * 
     * @var \Magento\Config\Model\Config\Source\Locale\Country
     */
    protected $_countryOptions;

    /**
     * Country options
     * 
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $_booleanOptions;

    /**
     * Sample Multiselect options
     * 
     * @var \Edd\ShopByPrice\Model\Post\Source\SampleMultiselect
     */
    protected $_sampleMultiselectOptions;

    /**
     * constructor
     * 
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Config\Model\Config\Source\Locale\Country $countryOptions
     * @param \Magento\Config\Model\Config\Source\Yesno $booleanOptions
     * @param \Edd\ShopByPrice\Model\Post\Source\SampleMultiselect $sampleMultiselectOptions
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Config\Model\Config\Source\Locale\Country $countryOptions,
        \Magento\Config\Model\Config\Source\Yesno $booleanOptions,
        \Edd\ShopByPrice\Model\Post\Source\SampleMultiselect $sampleMultiselectOptions,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    )
    {
        $this->_wysiwygConfig            = $wysiwygConfig;
        $this->_countryOptions           = $countryOptions;
        $this->_booleanOptions           = $booleanOptions;
        $this->_sampleMultiselectOptions = $sampleMultiselectOptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Edd\ShopByPrice\Model\Post $post */
        $post = $this->_coreRegistry->registry('edd_shopbyprice_post');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('post_');
        $form->setFieldNameSuffix('post');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Post Information'),
                'class'  => 'fieldset-wide'
            ]
        );
        $fieldset->addType('image', 'Edd\ShopByPrice\Block\Adminhtml\Post\Helper\Image');
        $fieldset->addType('file', 'Edd\ShopByPrice\Block\Adminhtml\Post\Helper\File');
        if ($post->getId()) {
            $fieldset->addField(
                'post_id',
                'hidden',
                ['name' => 'post_id']
            );
        }
        $fieldset->addField(
            'name',
            'text',
            [
                'name'  => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'price_min',
            'text',
            [
                'name'  => 'price_min',
                'label' => __('Price Min'),
                'title' => __('Price Min'),
            ]
        );
      $fieldset->addField(
        'price_max',
        'text',
        [
          'name'  => 'price_max',
          'label' => __('Price Max'),
          'title' => __('Price Max'),
        ]
      );
        $fieldset->addField(
            'status',
            'select',
            [
                'name'  => 'status',
                'label' => __('Enabled'),
                'title' => __('Enabled'),
                'values' => $this->_booleanOptions->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'featured_image',
            'file',
            [
                'name'  => 'featured_image',
                'label' => __('Featured Image'),
                'title' => __('Featured Image'),
            ]
        );

        $postData = $this->_session->getData('edd_shopbyprice_post_data', true);
        if ($postData) {
            $post->addData($postData);
        } else {
            if (!$post->getId()) {
                $post->addData($post->getDefaultValues());
            }
        }
        $form->addValues($post->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Post');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
