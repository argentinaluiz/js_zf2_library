<?php

namespace JS\Stdlib\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;
use JS\Filter\JSNumberFormat;
use JS\Filter\NumberToLocalized;

class IntStrategy extends DefaultStrategy {

    public function extract($value) {
        $filter = new JSNumberFormat([
            'locale' => 'pt_BR',
            'number_format' => '#,##,##0'
        ]);
        return $filter->filter($value);
    }

    public function hydrate($value) {
        $filter = new NumberToLocalized(['locale' => 'pt_BR']);
        return $filter->filter($value);
    }

}
