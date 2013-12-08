<?php

namespace JS\Translator;

class Translator extends \Zend\I18n\Translator\Translator {

    private $textDomain = 'default';

    public function __construct($textDomain = 'default', $locale = null) {
        $this->setTextDomain($textDomain);
        $this->setLocale($locale);
    }

    public function getTextDomain() {
        return $this->textDomain;
    }

    public function setTextDomain($textDomain) {
        $this->textDomain = $textDomain;
        return $this;
    }

    public function translate($message) {
        return parent::translate($message, $this->getTextDomain(), $this->getLocale());
    }

}
