<?php

namespace JS\Test;

class JSTestCase extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Zend\Mvc\Application
     */
    protected $application;

    use JSZendFunctionsTrait;

    public static function setUpBeforeClass() {
        JSDatabaseTest::createDataBase();
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass() {
        JSDatabaseTest::dropDatabase();
        parent::tearDownAfterClass();
    }

    protected function setUp() {
        $bootstrap = new JSBootstrap();
        $this->application = $bootstrap->getBootstrap();
        $this->setApplicationInstance($this->application);
        JSDatabaseTest::createTables($this->getEntityManager());
        parent::setUp();
    }

    protected function tearDown() {
        parent::tearDown();
    }

    public function getApplication() {
        return $this->application;
    }

}
