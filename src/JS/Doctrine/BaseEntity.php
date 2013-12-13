<?php

namespace JS\Doctrine;

use Zend\Stdlib\Hydrator\ClassMethods;

class BaseEntity {

    public function __construct(Array $data = array()) {
        $this->hydrate($data);
    }

    public function hydrate(Array $data = array()) {
        $hydrator = new ClassMethods();
        $hydrator->hydrate($data, $this);
    }

    public function extract() {
        $hydrator = new ClassMethods();
        return $hydrator->extract($this);
    }

}
