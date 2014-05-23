<?php

namespace JS\Test;

class JSTestCase extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Zend\Mvc\Application
     */
    protected static $application;

    use JSZendFunctionsStaticTrait;

    public static function setUpBeforeClass() {
        JSDatabaseTest::createDataBase();
        self::setApplicationInstance((new JSBootstrap())->getBootstrap());
        JSDatabaseTest::createTables(self::getEntityManager());
    }

    public static function tearDownAfterClass() {
        JSDatabaseTest::dropDatabase();
    }

    public function getApplication() {
        return self::$applicationInstance;
    }

}
