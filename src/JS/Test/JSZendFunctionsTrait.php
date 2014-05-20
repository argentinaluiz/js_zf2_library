<?php

namespace JS\Test;

trait JSZendFunctionsTrait {

    /**
     * @var \Zend\Mvc\Application
     */
    private $applicationInstance;

    public function setApplicationInstance($application) {
        $this->applicationInstance = $application;
    }

    public function getConfig() {
        return $this->applicationInstance->getConfig();
    }

    public function getServiceManager() {
        return $this->applicationInstance->getServiceManager();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
    }

    public function getControllerManager() {
        return $this->getServiceManager()->get('ControllerManager');
    }

    public function getInputFilterManager() {
        return $this->getServiceManager()->get('InputFilterManager');
    }

}
