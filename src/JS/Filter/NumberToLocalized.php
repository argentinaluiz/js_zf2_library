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
     * Construtor
     * @param array $options Opcoes com parametros 'locale'[,'precision']
     * key precision é facultativo
     * array(
     *  'locale' => 'pt_BR',
     *  'precision' => 2
     * )
     */
    public function __construct($options = []) {
        $this->setOptions($options);
    }

    /**
     * Atribuir o locale
     * @param string|\Zend_Locale $locale local para conversão
     * 'pt_BR' ou new \Zend_Locale('pt_BR')
     */
    public function setLocale($locale) {
        $this->options['locale'] = $locale;
    }

    /**
     * Pegar o locale atual
     * @param string $locale
     * @throws Zend_Locale_Exception Quanto nao tem locale ou nao pode ser identificado
     * @return string
     */
    public function getLocate() {
        return Zend_Locale::findLocale(isset($this->options['locale']) ? $this->options['locale'] : 'pt_BR');
    }

    /**
     * Atribuir o numero de casas decimais
     * @param int $precision numero de casais decimais
     * Se o $precision for null ou nao for inteiro ele nao e atribuido
     */
    public function setPrecision($precision = null) {
        if ($precision != null && is_int($precision))
            $this->options['precision'] = $precision;
    }

    /**
     * Pegar o precision
     * @param int $precision
     * Se o precision nao existir retorna null
     */
    public function getPrecision() {
        return isset($this->options['precision']) ? (int) $this->options['precision'] : null;
    }

    /**
     * Filtra o numero pra o formato brasileiro, se for americano,
     * senao pra brasileiro
     * @param string $value Numero americano ou brasileiro para ser convertido
     */
    public function filter($value) {
        /*
         * Se o numero não for da moeda em questao, pode estar vindo do db
         */
        if (!Zend_Locale_Format::isNumber($value, $this->options)) {
            return Zend_Locale_Format::toNumber($value, $this->options);
        } else {
            /* Se o numero for da moeda em questao, pode estar vindo do front-end */
            return Zend_Locale_Format::getNumber($value, $this->options);
        }
        return $value;
    }

}
