<?php

class Vignates_Translate_Model_Observer{
    
    public function addButton()
    {
        $productId = Mage::app()->getRequest()->getParam('id');        
        // Retrieve layout
        $layout = Mage::app()->getLayout();

        // Retrieve product_edit block
        $productEditBlock = $layout->getBlock('product_edit');

        // Retrieve original "Save and Continue Edit" button
        $saveAndContinueButton = $productEditBlock->getChild('save_and_edit_button');

        // Create new button
        $myButton = $layout->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'     => Mage::helper('translate')->__('Translate'),
                'onclick'   => 'setLocation(\'' . $this->getButtonUrl($productId) . '\')',
                'class'  => 'save translate'
            ));

        // Create a container that will gather existing "Save and Continue Edit" button and the new button
        $container = $layout->createBlock('core/text_list', 'button_container');

        // Append existing "Save and Continue Edit" button and the new button to the container
        $container->append($saveAndContinueButton);
        $container->append($myButton);

        // Replace the existing "Save and Continue Edit" button with our container
        $productEditBlock->setChild('save_and_edit_button', $container);
    }

     public function getButtonUrl($id)
    {
        // The URL called fits to the controller of our module: Herve_ProductEditButton_Adminhtml_ButtonController
        return Mage::getModel('adminhtml/url')->getUrl('translate/adminhtml_index/index', array(
            '_current'   => true,
            'back'       => 'edit',
            'tab'        => '{{tab_id}}',
            'id' => $id
        ));
    }

}