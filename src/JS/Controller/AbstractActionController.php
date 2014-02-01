<?php

namespace JS\Controller;

use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;

class AbstractActionController extends ZendAbstractActionController {

    /**
     * Funcao chamada quanto uma acao nao e encontrada.
     * Se requisição e via XmlHttpRequest lanca uma excecao para url nao encontrada.
     * Senao chama a funcao do proprio Zend para acoes nao encontrada e retornada a pagina.
     */
    public function notFoundAction() {
        if (!$this->getRequest()->isXmlHttpRequest())
            parent::notFoundAction();
        else {
            $this->jsResponse()->error("Url Não Encontrada");
            $this->jsLog()->log(new \Exception('Url Não Encontrada'));
            return $this->getResponse();
        }
    }

    public function basePath() {
        return $this->getViewHelper()->basePath();
    }

    /**
     * @return \Zend\View\Renderer\PhpRenderer
     */
    public function getViewHelper() {
        return $this->getServiceLocator()->get('Zend\View\Renderer\RendererInterface');
    }

    public function getFormManager() {
        return $this->getServiceLocator()->get('FormElementManager');
    }

}
