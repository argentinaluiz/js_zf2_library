<?php

namespace JS\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\Mvc\Controller\ServiceManager\ServiceLocatorInterface;
use JS\Service\Log;

class LogFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $log = new Log();
        $writer_firebug = new \Zend\Log\Writer\FirePhp();
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer_firebug);
        $log->setLog($logger);
        return $log;
    }

}

