<?php

namespace JS\Stdlib\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class DateTimeStrategy extends DefaultStrategy {

    public function extract($value) {
        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d');
        }
        return $value;
    }

    public function hydrate($value) {
        if (is_string($value))
            $value = \DateTime::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        return $value;
    }

}
