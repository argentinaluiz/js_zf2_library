<?php

namespace JS\Template\Messages;

/**
 * Interface para Messages
 * @author Luiz Carlos
 */
interface MessageInterface {

    const INFO = 'info';
    const ERROR = 'error';
    const NOTICE = 'notice';

    public static function message($msg, $priority = self::ERROR);
    
    public static function info($msg);

    public static function error($msg);

    public static function notice($msg);
}