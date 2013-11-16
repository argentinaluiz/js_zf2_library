<?php

namespace JS\Exception;

interface BaseEntityInterfaceException extends BaseInterfaceException{

    public function getEntity();

    public function setEntity($entity);
}
