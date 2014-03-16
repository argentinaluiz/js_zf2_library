<?php

namespace JS\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LogFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->getServiceLocator()->get('Config');
        $pathLog = $config['js_library']['path_log'];
        if (empty($pathLog))
            throw new \Exception("Especique o arquivo para armazenar o log");
        $log = new \JS\Plugin\Log();
        $writer = 'production' == getenv('APPLICATION_ENV') ?
                new \Zend\Log\Writer\Stream($pathLog) :
                new Zend\Log\Writer\FirePhp();
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $log->setLog($logger);
        return $log;
    }

}
