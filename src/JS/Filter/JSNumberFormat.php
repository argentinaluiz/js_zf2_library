<?php

/**
 * Filtro baseado em Zend Framework 1 usando Zend Framework 2 para converter numeros americanos.
 * Alternativa para quem não tem a disponibilidade da extensão intl. Se tiver
 * a classe NumberFormat da pasta I18N ja faz a conversao
 * em formatos locais
 * @link http://framework.zend.com/manual/2.0/en/modules/zend.i18n.filters.html#numberformat Endereço do filtro NumberFormat do zf2
 * @author Luiz Carlos <argentinaluiz@gmail.com>
 */

namespace JS\Filter;

use \Zend_Locale;
use Zend\Filter\AbstractFilter;

class JSNumberFormat extends AbstractFilter {

    /**
     * Construtor
     * key precision é facultativo
     * array(
     *  'locale' => 'pt_BR',
     *  'precision' => 2
     * )
     * @param array $options Opcoes com parametros 'locale'[,'precision']
     */
    public function __construct($options = []) {
        $this->setOptions($options);
    }

    /**
     * Atribuir o locale
     * 'pt_BR' ou new \Zend_Locale('pt_BR')
     * @param string|\Zend_Locale $locale local para conversão
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
     * Se o $precision for null ou nao for inteiro ele nao e atribuido
     * @param int $precision numero de casais decimais
     */
    public function setPrecision($precision = null) {
        if ($precision != null && is_int($precision)) {
            $this->options['precision'] = $precision;
        }
    }

    /**
     * Pegar o precision
     * Se o precision nao existir retorna null
     * @param int $precision
     */
    public function getPrecision() {
        return isset($this->options['precision']) ? (int) $this->options['precision'] : null;
    }

    public function setNumberFormat($numberFormat) {
        if ($numberFormat != null) {
            $this->options['number_format'] = $numberFormat;
        }
    }

    public function getNumberFormat() {
        return isset($this->options['number_format']) ? (int) $this->options['number_format'] : null;
    }

    /**
     * Retorna o numero formatado dependendo das opcoes
     * ##0.#  -> 12345.12345 -> 12345.12345
     * ##0.00 -> 12345.12345 -> 12345.12
     * ##,##0.00 -> 12345.12345 -> 12,345.12
     *
     * @param   string  $value Numero americano
     * @return  string  locale Numero formatado
     * @throws Zend_Locale_Exception
     */
    public function filter($value) {
        return \Zend_Locale_Format::toNumber($value, $this->options);
    }

}
