<?php

namespace JS\Test;

use Doctrine\ORM\EntityManager;

class JSDatabaseTest {

    private $entityManager;

    public function __construct(EntityManager $entityManager = null) {
        $this->entityManager = $entityManager;
    }

    public static function createDataBase() {
        $config = include getcwd() . '/config/autoload/doctrine.test.php';
        $params = $config['doctrine']['connection']['orm_default']['params'];
        $host = $params['host'];
        $user = $params['user'];
        $password = $params['password'];
        $dbname = $params['dbname'];
        $dbh = new \PDO("mysql:host=$host", $user, $password);
        $dbh->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8 COLLATE utf8_general_ci;");
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

    public static function createTables($entityManager) {

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);

        $cmf = $entityManager->getMetadataFactory();
        $classes = $cmf->getAllMetadata();

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);
    }

}
