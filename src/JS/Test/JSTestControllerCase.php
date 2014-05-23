<?php

namespace JS\Test;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\Router\Console\RouteMatch;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

abstract class JSTestControllerCase extends AbstractHttpControllerTestCase {

    use JSZendFunctionsTrait;

    public static function setUpBeforeClass() {
        JSDatabaseTest::createDataBase();
    }

    public static function tearDownAfterClass() {
        JSDatabaseTest::dropDatabase();
    }

    protected function setUp() {
        $this->setApplicationConfig(JSBootstrap::getConfig());
        parent::setUp();
        $this->setApplicationInstance($this->getApplication());
        JSDatabaseTest::createTables($this->getEntityManager());
    }

    protected function tearDown() {
        parent::tearDown();
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
