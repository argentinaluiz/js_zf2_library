<?php

namespace JS\Test;

trait JSZendFunctionsStaticTrait {

    /**
     * @var \Zend\Mvc\Application
     */
    public static $applicationInstance;

    public static function setApplicationInstance($application) {
        self::$applicationInstance = $application;
    }

    public static function getConfig() {
        return self::$applicationInstance->getConfig();
    }

    public static function getServiceManager() {
        return self::$applicationInstance->getServiceManager();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public static function getEntityManager() {
        return self::getServiceManager()->get('Doctrine\ORM\EntityManager');
    }

    public static function getControllerManager() {
        return self::getServiceManager()->get('ControllerManager');
    }

    public static function getInputFilterManager() {
        return self::getServiceManager()->get('InputFilterManager');
    }

}
