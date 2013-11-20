<?php

namespace JS\Stdlib\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class SelectStrategy extends DefaultStrategy {

    private $function;

    /**
     * @param $function ex.: getCodigo
     */
    public function __construct($function = null) {
        if ($function)
            $this->function = $function;
        else
            $this->function = "getCodigo";
    }

    public function extract($value) {
        if ($value)
            return $value->{$this->function}();
        return $value;
    }

}
