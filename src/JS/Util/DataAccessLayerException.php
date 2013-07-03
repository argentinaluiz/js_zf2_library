<?php

namespace JS\Util;

class DataAccessLayerException extends \Exception {
    /* Redefine a exceção para que a mensagem não seja opcional */

    private $labelMessage = null;
    private $type;

    public function __construct($message = "", $code = 0, $type = 'error', $previous = null) {
        /* Garante que tudo é atribuído corretamente */
        $this->type = $type;
        parent::__construct($message, $code, $previous);
    }

    /* Representação do objeto personalizada no formato string */

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getLabelMessage() {
        return $this->labelMessage;
    }

    public function setLabelMessage($msg) {
        $this->labelMessage = $msg;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

}

?>
