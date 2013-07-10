<?php

namespace JS\Validator;

use Zend\Validator\AbstractValidator;
use \Zend_Registry;
use \Zend_Locale;
use \Zend_Locale_Format;

/**
 * Classe JSFloat para validar numeros flutuantes baseado
 * no codigo Float.php do zf1 e implementando a interface
 * AbstractorValidator do zf2
 */
class JSFloat extends AbstractValidator {

    const INVALID = 'floatInvalid';
    const NOT_FLOAT = 'notFloat';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID => "Invalid type given. String, integer or float expected",
        self::NOT_FLOAT => "'%value%' does not appear to be a float",
    );
    
    protected $locale;

    /**
     * Constructor for the float validator
     *
     * @param string|Zend_Locale $locale
     */
    public function __construct($options = array()) {
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
     * Returns true if and only if $value is a floating-point value
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value) {
        if (!is_string($value) && !is_int($value) && !is_float($value)) {
            $this->setValue(self::INVALID);
            return false;
        }

        if (is_float($value)) {
            return true;
        }

        $this->setValue($value);
        try {
            if (!Zend_Locale_Format::isFloat($value, array('locale' => $this->locale))) {
                $this->setValue(self::NOT_FLOAT);
                return false;
            }
        } catch (Zend_Locale_Exception $e) {
            $this->setValue(self::NOT_FLOAT);
            return false;
        }

        return true;
    }

}
