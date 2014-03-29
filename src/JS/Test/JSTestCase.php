<?php

namespace JS\Test;

class JSTestCase extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Zend\Mvc\Application
     */
    protected $application;

    public static function createDataBase() {
        $config = include getcwd() . '/config/autoload/doctrine.test.php';
        $params = $config['doctrine']['connection']['orm_default']['params'];
        $host = $params['host'];
        $user = $params['user'];
        $password = $params['password'];
        $dbname = $params['dbname'];
        $dbh = new \PDO("mysql:host=$host", $user, $password);
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
        $bootstrap = new JSBootstrap();
        $this->application = $bootstrap->getBootstrap();
        $this->createTables();
        parent::setUp();
    }

    protected function tearDown() {
        parent::tearDown();
    }

    public function getConfig() {
        return $this->application->getConfig();
    }

    public function getServiceManager() {
        return $this->application->getServiceManager();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
    }

}
