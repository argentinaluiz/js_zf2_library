<?php

namespace JS\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Classe generica para os controllers do Zend Framework 2
 * @author Luiz Carlos <argentinaluiz@gmail.com>
 */

/**
 * Base controller
 *
 * Convenience methods for pre-built plugins (@see __call):
 *
 * @method \JS\Plugin\DataTable jsDataTable
 * @method \JS\Plugin\Format jsFormat
 * @method \JS\Plugin\JSArray jsArray
 * @method \JS\Plugin\JSResponse jsResponse
 * @method \JS\Plugin\JSMessage jsMessage
 * @method \JS\Plugin\Log jsLog
 */
class BaseController extends AbstractActionController {

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

    public function onDispatch(\Zend\Mvc\MvcEvent $e) {
        parent::onDispatch($e);
    }
    
    protected function getViewHelper() {
        return $this->getServiceLocator()->get('Zend\View\Renderer\RendererInterface');
    }

}