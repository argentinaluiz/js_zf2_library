<?php

namespace JS\Validator;

use Zend\Validator\NotEmpty;

class JSNotEmpty extends NotEmpty {

    public function __construct($options = []) {
        parent::__construct($options);
        $this->setMessage('Este campo é requerido.', self::IS_EMPTY);
    }

}
