<?php

namespace tests;

chdir(dirname(__DIR__));

include __DIR__ . '/../init_autoloader.php';

class Bootstrap {

    /**
     * @var \Zend\Mvc\Application
     */
    protected static $bootstrap;

    public function init() {
        self::$bootstrap = \Zend\Mvc\Application::init(include 'config/test.config.php');
    }

    /**
     * @return \Zend\Mvc\Application
     */
    public static function getBootstrap() {
        return static::$bootstrap;
    }

    public static function getConfig() {
        return include 'config/test.config.php';
    }

}

ob_start();
Bootstrap::init();

