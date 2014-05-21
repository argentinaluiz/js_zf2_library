<?php

/**
 * Filtro baseado em Zend Framework 1 usando Zend Framework 2 para converter numeros.
 * Se americanos para brasileiro, se brasileiro para americanos
 * @author Luiz Carlos <argentinaluiz@gmail.com>
 */

namespace JS\Filter;

use Zend\Filter\AbstractFilter;
use \Zend_Locale;
use \Zend_Locale_Format;

class NumberToLocalized extends AbstractFilter {

    /**
     * Filtra o numero pra o formato brasileiro, se for americano,
     * senao pra brasileiro
     * @param string $value Numero americano ou brasileiro para ser convertido
     */
    public function filter($value) {
        if (!Zend_Locale_Format::isNumber($value, ['locale' => new Zend_Locale('pt_BR')])) {
            return Zend_Locale_Format::toNumber($value, ['locale' => new Zend_Locale('pt_BR')]);
        } else {
            return Zend_Locale_Format::getNumber($value, ['locale' => new Zend_Locale('pt_BR')]);
        }
        return $value;
    }

}
