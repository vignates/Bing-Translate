<?php

class Vignates_Translate_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $defaultStoreId = Mage::app()->getWebsite()->getDefaultGroup()->getDefaultStoreId();
        $secretKey = $this->getRequest()->getParam('key');
        if ($secretKey) {
            $productId = (int) $this->getRequest()->getParam('id');
            $productResource = Mage::getResourceModel('catalog/product');
            $attributes = Mage::helper('translate')->getAttributesToTranslate();
            $allStores = Mage::app()->getStores();
            $defaultStoreAttributeValues = array();
            foreach ($attributes as $attribute) {
                $defaultStoreAttributeValues[$attribute] = $productResource->getAttributeRawValue($productId, $attribute);
            }

            foreach ($allStores as $store) {
                $storeId = $store->getId();
                $isLangAvailable = Mage::helper('translate')->isLangAvailable($store->getCode());
                if (($defaultStoreId != $storeId) && ($isLangAvailable)) {
                    $storeViewAttr = array();
                    $translatedToLanguage = Mage::helper('translate')->extractLangCodes($store->getCode());
                    foreach ($attributes as $attribute) {
                        try {
                            //$storeViewContent = $productResource->getAttributeRawValue($productId, $attribute, $storeId);
                            //if ($storeViewContent == $defaultStoreAttributeValues[$attribute]) {
                                $storeViewContents = Mage::helper('translate')->getTranslatedContent($defaultStoreAttributeValues[$attribute], $translatedToLanguage);
                                if ($storeViewContents == "ERROR") {
                                    Mage::throwException("Translation failed. Please manually edit the translation or check for gabage characters in the texts.");
                                }
                                $storeViewAttr[$attribute] = html_entity_decode($storeViewContents);
                           // }
                        } catch (Mage_Core_Exception $e) {
                            Mage::getSingleton('core/session')->addError($e->getMessage());
                             $this->_redirectReferer();
                            return;
                        }
                    }
                    if($translatedToLanguage != "en"){
                    Mage::getSingleton('catalog/product_action')->updateAttributes(array($productId), $storeViewAttr, $storeId);
                    }
                }
            }
            $this->_getSession()->addSuccess($this->__('Product has been translated.'));
        }
        $this->_redirectReferer();
    }

}