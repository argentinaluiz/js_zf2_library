<?php

namespace JS\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BaseServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $service = new BaseService($entityManager, $serviceLocator->get('jstranslator'), 'Entity');
        $service->setServiceLocator($serviceLocator);
        return $service;
    }

}
