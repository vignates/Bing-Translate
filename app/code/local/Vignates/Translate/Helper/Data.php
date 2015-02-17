<?php
class Vignates_Translate_Helper_Data extends Mage_Core_Helper_Abstract
{
      private $from = 'en', $cast, $attributes;

    public function __construct() {
        $this->translator = new MicrosoftTranslator($this->getApiKey());
    }

    public function getAttributesToTranslate() {
        $this->attributes = Mage::getStoreConfig("vignates_translate/general/attributes_to_translate");
        $this->attributes = explode(",", $this->attributes);
        foreach ($this->attributes as $k => $v) {
            $this->attributes[$k] = trim($v);
        }

        return $this->attributes;
    }

    public function getApiKey() {
        return Mage::getStoreConfig("vignates_translate/bing_translate/api_key");
    }

    public function getTranslatedContent($content, $translatedTo) {
        if($this->from == $translatedTo || empty($content)){
            return $content;
        }

        $this->translator->translate($this->from, $translatedTo, $content);
        $this->cast = (array) $this->translator->response;

        if($this->cast['status'] == "ERROR"){
            return $this->cast['status'];
        }
        return strip_tags($this->cast['translation']);
    }

    public function isLangAvailable($langCode) {
        if (!in_array($this->extractLangCodes($langCode), $this->translator->getLanguages())) {
            return false;
        }
        return true;
    }

    public function extractLangCodes($codes) {
        $string = str_replace('_', '-', $codes);
        $pos = strpos($string, "-");
        if (!empty($pos)) {
            $afterExtract = substr($string, $pos + 1);
        } else {
            $afterExtract = $string;
        }
        return $afterExtract;
    }
}
	 