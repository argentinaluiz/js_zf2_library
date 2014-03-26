<?php

/**
 * Plugin baseado em Zend Framework 2 para enviar respostas
 * html, 
 * e array de consultas
 * para ser ordenada a consulta no banco de dados
 * @link http://datatables.net/examples/data_sources/server_side.html
 * @author Luiz Carlos <argentinaluiz@gmail.com>
 */

namespace JS\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class JSResponse extends AbstractPlugin {

    private $event;

    public function html($msg) {
        $response = $this->getEvent()->getResponse();
        $response->getHeaders()
                ->addHeaders(['Content-Type' => 'text/html']);
        $response->setContent($msg);
        return $response;
    }

    public function error($msg, $template = \JS\Template\Messages\MessageFactory::MESSAGE_BOOTSTRAP) {
        $response = $this->getEvent()->
                getResponse()->
                setStatusCode(400);
        $response->getHeaders()->
                addHeaders(['Content-Type' => 'text/html']);
        $response->setContent(\JS\Template\Messages\MessageFactory::message($msg, $template, \JS\Template\Messages\MessageInterface::ERROR));
        return $response;
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
            $params = [];
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
