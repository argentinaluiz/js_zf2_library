<?php

chdir(dirname(__DIR__));

include __DIR__ . '/../init_autoloader.php';
ob_start();
\Zend\Mvc\Application::init(include 'config/test.config.php');
