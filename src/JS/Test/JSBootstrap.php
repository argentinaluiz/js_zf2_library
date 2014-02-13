<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace JS\Test;

/**
 * Description of JSBootstrap
 *
 * @author Luiz
 */
class JSBootstrap {

    /**
     * @var \Zend\Mvc\Application
     */
    protected $bootstrap;

    public function __construct() {
        $this->bootstrap = \Zend\Mvc\Application::init(include 'config/test.config.php');
    }

    /**
     * @return \Zend\Mvc\Application
     */
    public function getBootstrap() {
        return $this->bootstrap;
    }

    public static function getConfig() {
        return include 'config/test.config.php';
    }

}
