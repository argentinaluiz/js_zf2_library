<?php

namespace JS\Template\Messages;

abstract class AbstractMessage implements MessageInterface {

    public static function message($msg, $priority = self::ERROR) {
        switch ($priority) {
            case self::INFO:
                return static::info($msg);
            case self::ERROR:
                return static::error($msg);
            case self::NOTICE:
                return static::notice($msg);
        }
    }

    public static function getListPriorityMessage() {
        return array(
            self::INFO,
            self::ERROR,
            self::NOTICE
        );
    }

}