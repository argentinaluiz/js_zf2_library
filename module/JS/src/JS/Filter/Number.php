<?php

namespace JS\Filter;

use Zend\Filter\AbstractFilter;
use \Zend_Locale;
use \Zend_Locale_Format;

class Number extends AbstractFilter {

    public function filter($value) {
        if (!Zend_Locale_Format::isNumber($value, array('locale' => new Zend_Locale('pt_BR')))) {
            return Zend_Locale_Format::toNumber($value, array('locale' => new Zend_Locale('pt_BR')));
        } else {
            return Zend_Locale_Format::getNumber($value, array('locale' => new Zend_Locale('pt_BR')));
        }

        return $value;
    }

}