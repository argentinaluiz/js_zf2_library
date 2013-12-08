<?php

namespace JS\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use JS\Translator\Translator;

class BaseServiceFactory implements FactoryInterface {

    protected $textDomain = 'js';
    protected $entityName = '';

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $service = new BaseService($entityManager, $this->getTranslator($serviceLocator), $this->getEntityName());
        $service->setServiceLocator($serviceLocator);
        return $service;
    }

    private function getTranslator($serviceLocator) {
        $config = $serviceLocator->get('Configuration');
        $trConfig = isset($config['translator']) ? $config['translator'] : array();
        $translator = Translator::factory($trConfig);
        $translator->setTextDomain($this->textDomain);
        return $translator;
    }

    private function getEntityName() {
        return $this->entityName;
    }

}
