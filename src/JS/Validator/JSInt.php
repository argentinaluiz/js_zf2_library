<?php

namespace JS\Validator;

use Zend\Validator\AbstractValidator;
use \Zend_Registry;
use \Zend_Locale;
use \Zend_Locale_Format;

/**
 * Classe JSint para validar numeros flutuantes baseado
 * no codigo Int.php do zf1 e implementando a interface
 * AbstractorValidator do zf2
 */
class JSInt extends AbstractValidator {

    const INVALID = 'intInvalid';
    const NOT_INT = 'notInt';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID => "Invalid type given. String or integer expected",
        self::NOT_INT => "'%value%' does not appear to be an integer",
    ];
    protected $locale;

    /**
     * Constructor for the integer validator
     *
     * @param string|Zend_Locale $locale
     */
    public function __construct($options = []) {

        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (array_key_exists('locale', $options)) {
            $this->setLocale($options['locale']);
        } else {
            if (Zend_Registry::isRegistered('Zend_Locale')) {
                $locale = Zend_Registry::get('Zend_Locale');
                $this->setLocale($locale);
            }
        }
        parent::__construct($options);
    }

    /**
     * Returns the set locale
     */
    public function getLocale() {
        return $this->locale;
    }

    /**
     * Sets the locale to use
     *
     * @param string|Zend_Locale $locale
     */
    public function setLocale($locale = null) {
        $this->locale = Zend_Locale::findLocale($locale);
        return $this;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is a valid integer
     *
     * @param  string|integer $value
     * @return boolean
     */
    public function isValid($value) {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            $this->error(self::INVALID);
            return false;
        }

        if (is_int($value)) {
            return true;
        }

        $this->setValue($value);
        if ($this->locale === null) {
            $locale = localeconv();
            $valueFiltered = str_replace($locale['decimal_point'], '.', $value);
            $valueFiltered = str_replace($locale['thousands_sep'], '', $valueFiltered);

            if (strval(intval($valueFiltered)) != $valueFiltered) {
                $this->error(self::NOT_INT);
                return false;
            }
        } else {
            try {
                if (!Zend_Locale_Format::isInteger($value, ['locale' => $this->locale])) {
                    $this->error(self::NOT_INT);
                    return false;
                }
            } catch (Zend_Locale_Exception $e) {
                $this->error(self::NOT_INT);
                return false;
            }
        }

        return true;
    }

}
