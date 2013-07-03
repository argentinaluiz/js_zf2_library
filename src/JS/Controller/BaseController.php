<?php

namespace JS\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class BaseController extends AbstractActionController {

    public function notFoundAction() {
        if (!$this->getRequest()->isXmlHttpRequest())
            parent::notFoundAction();
        else {
            $this->msg()->msgError("Ação Não Encontrada");
            return $this->getResponse();
        }
    }

    public function onDispatch(\Zend\Mvc\MvcEvent $e) {
        parent::onDispatch($e);
    }

}