<?php

namespace JS\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Log\Logger;

class Log extends AbstractPlugin {

    private $event;

    /**
     * @return \Zend\Log\Logger
     */
    private $log;

    /**
     * @param \Exception $ex
     * @param mixed $priority
     */
    public function log($ex, $priority = Logger::ERR) {
        switch ($priority) {
            case "error":
                $priority = Logger::ERR;
                break;
            case "notice":
                $priority = Logger::NOTICE;
                break;
        }
        if ($log = $this->getLog()) {
            try {
                if ($this->getEvent()->getRequest()->isPost())
                    $params = $this->getEvent()->getRequest()->getPost()->toArray();
                else
                    $params = $this->getEvent()->getRequest()->getQuery()->toArray();
            } catch (\Exception $exc) {
                
            }
            $log->log($priority, $ex->getMessage());
            if (getenv('APPLICATION_ENV') == 'production') {
                if ($params)
                    $log->log($priority, print_r($params, true));
            }
            else {
                if ($params)
                    $log->log($priority, $params);
            }

            $log->log($priority, $ex);
        }
    }

    public function setLog($log) {
        $this->log = $log;
    }

    /**
     * @return \Zend\Log\Logger
     */
    private function getLog() {
        return $this->log;
    }

    /**
     * Get the event
     *
     * @return \Zend\Mvc\MvcEvent
     * @throws Exception\DomainException if unable to find event
     */
    private function getEvent() {
        if ($this->event) {
            return $this->event;
        }

        $controller = $this->getController();
        if (!$controller instanceof \Zend\Mvc\InjectApplicationEventInterface) {
            throw new \Exception('Forward plugin requires a controller that implements InjectApplicationEventInterface');
        }

        $event = $controller->getEvent();
        if (!$event instanceof \Zend\Mvc\MvcEvent) {
            $params = array();
            if ($event) {
                $params = $event->getParams();
            }
            $event = new MvcEvent();
            $event->setParams($params);
        }
        $this->event = $event;

        return $this->event;
    }

}