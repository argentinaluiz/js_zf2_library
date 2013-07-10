<?php

namespace JS\View\Helper;

use Zend\View\Helper\AbstractHelper;
use JS\Filter\JSNumberFormat as NumberFormat;

class JSNumberFormat extends AbstractHelper {

    public function __invoke($number, $precision = null, $locale = null) {
        $filter = new NumberFormat();
        $filter->setPrecision($precision);
        $filter->setLocale($locale);
        return $filter->filter($number);
    }

}
