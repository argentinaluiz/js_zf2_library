<?php

namespace JS\Doctrine;

use Zend\Stdlib\Hydrator\ClassMethods;

class BaseEntity {

    public function __construct(Array $data = array()) {
        $this->hydrate($data);
    }

    public function hydrate(Array $data = array()) {
        (new ClassMethods())->hydrate($data, $this);
    }

    public function extract() {
        return (new ClassMethods())->extract($this);
    }

}
