<?php

class Vignates_Translate_Model_Observer_Autoloader extends Varien_Event_Observer {

    public function controllerFrontInitBefore($event) {
        spl_autoload_register(array($this, 'load'), true, true);
    }

    public static function load($class) {
        $phpFile = Mage::getBaseDir('lib') . '/Bing/MicrosoftTranslator.class.php';
        require_once( $phpFile );
    }

}

