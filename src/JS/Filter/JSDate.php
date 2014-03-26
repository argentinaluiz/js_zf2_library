<?php

namespace JS\Filter;

use Zend\Filter\AbstractFilter;

class JSDate extends AbstractFilter {

    /**
     * @var string
     */
    protected $format = \DateTime::ISO8601;

    /**
     * @param array|Traversable $options
     */
    public function __construct($options = []) {
        $this->setOptions($options);
    }

    /**
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat($format) {
        $this->format = $format;
    }

    /**
     * @param  string $value
     * @return string
     */
    public function filter($value) {
        try {
            $date = (is_int($value)) ? new \DateTime($value) : \DateTime::createFromFormat($this->getFormat(), $value);
        } catch (\Exception $e) {
            $date = $value;
        }
        return $date;
    }

}
