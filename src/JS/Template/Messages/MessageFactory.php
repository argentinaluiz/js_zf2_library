<?php

namespace JS\Template\Messages;

/**
 * Description of MessageFactory
 *
 * @author Luiz Carlos
 */
class MessageFactory implements MessageFactoryInterface {

    /**
     * @return \JS\Template\Messages\AbstractMessage
     */
    public static function create($type = null) {
        switch ($type) {
            case self::MESSAGE_BOOTSTRAP:
                return new BootstrapMessage;
                break;
            case self::MESSAGE_JQUERY:
                return new JqueryMessage;
                break;
            default:
                return $this;
        }
    }

    public static function message($msg, $type = self::MESSAGE_BOOTSTRAP, $priority = self::ERROR) {
        return static::create($type)->message($msg, $priority);
    }

}
