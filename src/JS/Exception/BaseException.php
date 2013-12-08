<?php

namespace JS\Exception;

class BaseException extends \Exception implements BaseExceptionInterface, BaseEntityExceptionInterface {

    private $entity;

    public function __construct($message = "", $code = 0, $previous = null, $entity = null) {
        parent::__construct($message, $code, $previous);
        $this->entity = $entity;
    }

    public function getEntity() {
        return $this->entity;
    }

}
