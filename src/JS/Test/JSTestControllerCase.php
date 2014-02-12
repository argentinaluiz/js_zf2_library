<?php

namespace JS\Test;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\Router\Console\RouteMatch;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

abstract class JSTestControllerCase extends AbstractHttpControllerTestCase {

    public function init() {
        $this->createDataBase();
        $this->setApplicationConfig(\tests\Bootstrap::getConfig());
        $this->createTables();
    }

    public function createDataBase() {
        $config = include getcwd() . '/config/autoload/doctrine.test.php';
        $params = $config['doctrine']['connection']['orm_default']['params'];
        $host = $params['host'];
        $user = $params['user'];
        $password = $params['password'];
        $dbname = $params['dbname'];
        $dbh = new \PDO("mysql:host=$host", $user, $password);
        if (!$dbh->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8 COLLATE utf8_general_ci;")) {
            throw new \Exception(print_r($dbh->errorInfo(), true));
        }
        $dbh = null;
    }

    public function dropDatabase() {
        $config = include getcwd() . '/config/autoload/doctrine.test.php';
        $params = $config['doctrine']['connection']['orm_default']['params'];
        $host = $params['host'];
        $user = $params['user'];
        $password = $params['password'];
        $dbname = $params['dbname'];
        $dbh = new \PDO("mysql:host=$host", $user, $password);
        if (!$dbh->exec("DROP DATABASE IF EXISTS `$dbname`;")) {
            throw new \Exception(print_r($dbh->errorInfo(), true));
        }
        $dbh = null;
    }

    public function createTables() {
        $this->getEntityManager()->clear();
        $em = $this->getEntityManager();

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $cmf = $em->getMetadataFactory();
        $classes = $cmf->getAllMetadata();

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);
    }

    protected function setUp() {
        $this->init();
        parent::setUp();
    }

    protected function tearDown() {
        $this->dropDatabase();
        parent::tearDown();
    }

    public function getConfig() {
        return $this->getApplication()->getConfig();
    }

    public function getServiceManager() {
        return $this->getApplication()->getServiceManager();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
    }

    public function getController($controllerClass, $controllerName, $action) {
        $config = $this->getConfig();
        $controller = $config['controllers']['factories'][$controllerClass]($this->getServiceManager()->get('Controller\Loader'));
        $event = new MvcEvent();
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = TreeRouteStack::factory($routerConfig);

        $event->setRouter($router);
        $event->setRouteMatch(new RouteMatch(array('controller' => $controllerName, 'action' => $action)));
        $controller->setEvent($event);
        return $controller;
    }

}
