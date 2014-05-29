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
        if ($this->textDomain)
            $translator->setTextDomain($this->textDomain);
        $service = new $this->service($entityManager, $translator, $this->entityName);
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
