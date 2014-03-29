<?php

namespace JS\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BaseAbstractServiceFactory implements AbstractFactoryInterface {

    private $configKey = 'entity_services';

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $config = $serviceLocator->get('Config');
        if (key_exists($this->configKey, $config) && (in_array($requestedName, $config[$this->configKey]) || isset($config[$this->configKey][$requestedName]))) {
            $entityName = str_replace('Service', 'Entity', $requestedName);
            if (!class_exists($entityName)) {
                throw new \Zend\Di\Exception\ClassNotFoundException('Entidade Não Existe');
            }
            return true;
        }
        return false;
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $config = $serviceLocator->get('Config');
        if (isset($config[$this->configKey][$requestedName])) {
            $service = $config[$this->configKey][$requestedName];
            $textDomain = isset($service['translator']['text_domain']) ? $service['translator']['text_domain'] : null;
            $baseService = new BaseServiceFactory($service['entity'], $textDomain);
            return $baseService->createService($serviceLocator);
        } else {
            $entityName = str_replace('Service', 'Entity', $requestedName);
            $textDomain = $this->getTextDomain($serviceLocator, $requestedName);
            $baseService = new BaseServiceFactory($entityName, $textDomain);
            return $baseService->createService($serviceLocator);
        }
    }

    private function getTextDomain(ServiceLocatorInterface $serviceLocator, $service) {
        $translator = $serviceLocator->get('Config')['translator'];
        $textDomain = strtolower(explode('\\', ltrim($service, '\\'))[2]);
        foreach ($translator['translation_file_patterns'] as $value) {
            if (isset($value['text_domain'])) {
                if ($value['text_domain'] == $textDomain) {
                    return $textDomain;
                }
            }
        }
        return null;
    }

}
