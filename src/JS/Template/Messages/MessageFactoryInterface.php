<?php

namespace JS\Template\Messages;

/**
 *
 * @author Luiz Carlos
 */
interface MessageFactoryInterface {

    const MESSAGE_JQUERY = "jquery";
    const MESSAGE_BOOTSTRAP = "bootstrap";

    public static function create($type);
}
