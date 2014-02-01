<?php

namespace JS\Exception;

/**
 *
 * @author Luiz
 */
interface BaseInterfaceException {

    const PDO_ERROR_DELETE_REGISTRO = 1451;
    const ERROR_ENTITY_NOT_EXIST = 1;
    const ERROR_ENTITY_EXIST = 2;

    public function criarMessage();
}
