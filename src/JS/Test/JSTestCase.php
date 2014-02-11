<?php

namespace JS\Test;

class JSTestCase extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Zend\Mvc\Application
     */
    protected $application;

    public function init() {
        $this->createDataBase();
        $this->application = \tests\Bootstrap::getBootstrap();
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
