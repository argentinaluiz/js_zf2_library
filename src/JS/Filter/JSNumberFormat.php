<?php

namespace JS\Filter;

use \Zend_Locale;
use Zend\Filter\AbstractFilter;

class JSNumberFormat extends AbstractFilter {

    public function __construct($options = array()) {
        $this->setOptions($options);
    }

    public function setLocale($locale) {
        $this->options['locale'] = $locale;
    }

    public function getLocate() {
        return Zend_Locale::findLocale(isset($this->options['locale']) ? $this->options['locale'] : null);
    }

    public function setPrecision($precision = null) {
        if ($precision != null && is_int($precision))
            $this->options['precision'] = $precision;
    }

    public function getPrecision() {
        return isset($this->options['precision']) ? $this->options['precision'] : null;
    }

    public function filter($value) {
        return \Zend_Locale_Format::toNumber($value, $this->options);
    }

}