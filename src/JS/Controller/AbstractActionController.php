<?php

namespace JS\Controller;

use Zend\Mvc\Controller\AbstractActionController as ZendAbstractActionController;

abstract class AbstractActionController extends ZendAbstractActionController {

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

    /**
     * @return string
     */
    protected function basePath() {
        return $this->getViewHelper()->basePath();
    }

    /**
     * @return \Zend\View\Renderer\PhpRenderer
     */
    protected function getViewHelper() {
        return $this->getServiceLocator()->get('Zend\View\Renderer\RendererInterface');
    }

    /**
     * @return \Zend\Form\FormElementManager
     */
    protected function getFormElementManager() {
        return $this->getServiceLocator()->get('FormElementManager');
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager() {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }

}
