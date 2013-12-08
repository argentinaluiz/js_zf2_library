<?php

namespace JS\Exception;

interface BaseEntityExceptionInterface extends BaseExceptionInterface {

    public function getEntity();
}
