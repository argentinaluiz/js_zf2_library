<?php

namespace JS\Util;

use Zend\Stdlib\Hydrator\ClassMethods as CM;

trait ClassMethods {

    public function hydrate(Array $data = array()) {
        (new CM())->hydrate($data, $this);
    }

    public function extract() {
        return (new CM())->extract($this);
    }

}
