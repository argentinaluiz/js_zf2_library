<?php

namespace JS\Exception;

class BaseException extends \Exception implements BaseInterfaceException {

    public function __construct($message = "", $code = 0, $previous = null) {
        /* Garante que tudo é atribuído corretamente */
        parent::__construct($message, $code, $previous);
        $this->criarMessage();
    }

    public final function getErrorPdo() {
        if ($ex = $this->getPrevious()) {
            if ($ex instanceof \PDOException)
                return $ex->errorInfo[1];
        }
        return null;
    }

    public function getCodeAbsolut() {
        $codePdo = $this->getErrorPdo();
        $code = $codePdo ? $codePdo : $this->code;
        return $code;
    }

    public function criarMessage() {
        switch ($this->getCodeAbsolut()) {
            case self::PDO_ERROR_DELETE_REGISTRO:
                $this->message = "Não é permitido a exclusão deste registro. Pode ser"
                        . "que ele esteja relacionado com outros registros";
                break;
            case self::ERROR_ENTITY_NOT_EXIST:
                $this->message = "Registro Não Encontrado";
                break;
        }
    }

}
