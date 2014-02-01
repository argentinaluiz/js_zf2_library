<?php

namespace JS\Util;

class Serializor {

    public static function toArray($array) {
        unset($array["__isInitialized__"]);
        return $array;
    }

}

?>
