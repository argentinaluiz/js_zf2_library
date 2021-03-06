<?php

namespace JS\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BaseServiceFactory implements FactoryInterface {

    /**
     * @var string
     */
    protected $textDomain;

    /**
     * @var \JS\Translator\Translator
     */
    protected $entityName;
    protected $service = 'JS\Service\BaseService';

    public function __construct($entityName = null, $textDomain = null) {
        if ($entityName) {
            $this->setEntityName($entityName);
        }
        if ($textDomain) {
            $this->setTextDomain($textDomain);
        }
    }

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $translator = $serviceLocator->get('jstranslator');
        $service = new $this->service($entityManager, $translator, $this->getEntityName());
        if ($this->getTextDomain()) {
            $service->setTextDomain($this->getTextDomain());
        }
        $service->setServiceLocator($serviceLocator);
        return $service;
    }

    public function getTextDomain() {
        return $this->textDomain;
    }

    public function getEntityName() {
        return $this->entityName;
    }

    public function setTextDomain($textDomain) {
        $this->textDomain = $textDomain;
        return $this;
    }

    public function setEntityName($entityName) {
        $this->entityName = $entityName;
        return $this;
    }

}
