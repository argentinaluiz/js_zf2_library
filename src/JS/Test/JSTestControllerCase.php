<?php

namespace JS\Test;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\Router\Console\RouteMatch;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

abstract class JSTestControllerCase extends AbstractHttpControllerTestCase {

    public static function createDataBase() {
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

    public static function dropDatabase() {
        $config = include getcwd() . '/config/autoload/doctrine.test.php';
        $params = $config['doctrine']['connection']['orm_default']['params'];
        $host = $params['host'];
        $user = $params['user'];
        $password = $params['password'];
        $dbname = $params['dbname'];
        $dbh = new \PDO("mysql:host=$host", $user, $password);
        $dbh->exec("DROP DATABASE IF EXISTS `$dbname`;");
        $dbh = null;
    }

    public function createTables() {
        $entityManager = $this->getEntityManager();

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);

        $cmf = $entityManager->getMetadataFactory();
        $classes = $cmf->getAllMetadata();

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);
    }

    public static function setUpBeforeClass() {
        self::createDataBase();
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass() {
        self::dropDatabase();
        parent::tearDownAfterClass();
    }

    protected function setUp() {
        $this->setApplicationConfig(JSBootstrap::getConfig());
        $this->createTables();
        parent::setUp();
    }

    protected function tearDown() {
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
        $controller = $this->getServiceManager()->get('ControllerLoader')->get($controllerClass);
        $event = new MvcEvent();
        $routerConfig = isset($config['router']) ? $config['router'] : [];
        $router = TreeRouteStack::factory($routerConfig);

        $event->setRouter($router);
        $event->setRouteMatch(new RouteMatch(['controller' => $controllerName, 'action' => $action]));
        $controller->setEvent($event);
        return $controller;
    }

}
