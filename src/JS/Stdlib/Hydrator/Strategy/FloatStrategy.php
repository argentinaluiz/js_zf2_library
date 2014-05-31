<?php

namespace JS\Stdlib\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;
use JS\Filter\JSNumberFormat;
use JS\Filter\NumberToLocalized;

class FloatStrategy extends DefaultStrategy {

    public function extract($value, $options = null) {
        $op = [
            'locale' => 'pt_BR',
            'number_format' => '#,##,##0.00'
        ];
        if (!$options) {
            $op = $options;
        }
        $filter = new JSNumberFormat($op);
        return $filter->filter($value);
    }

    public function hydrate($value) {
        $filter = new NumberToLocalized(['locale' => 'pt_BR']);
        return $filter->filter($value);
    }

}
