<?php

namespace JS\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use JS\Exception\BaseException;

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
abstract class BaseController extends AbstractActionController {

    private $entity = null;
    private $formConsultar;
    private $formEditar;
    private $pageSize = 20;
    private $repository;
    private $routesAction = array(
        'save' => '',
        'save_and_new' => '',
        'save_and_close' => '',
        'delete' => ''
    );
    private $service;
    private $translator;
    protected $identifierName = 'codigo';

    /**
     * @todo routes disponiveis => array(
     * save, save_and_new, save_and_close, delete
     * )
     */
    abstract public function initRoutesAction();

    abstract public function find($opcoes);

    public function addRoutesAction($routeAction, $url) {
        $this->routesAction[$routeAction] = $url;
    }

    public function salvarAction() {
        $data = $this->params()->fromPost();
        $form = $this->getFormEditar();
        $entity = null;
        try {
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                if ($data[$this->getIdentifierName()] == "") {
                    $entity = $this->getService()->create($data);
                    $this->flashMessenger()->addMessage(array(
                        'info' => "<strong>" . $this->getTranslator()->translate('s_created') . "</strong>"
                    ));
                } else {
                    $entity = $this->getService()->update($data);
                    $this->flashMessenger()->addMessage(array(
                        'info' => "<strong>" . $this->getTranslator()->translate('s_created') . "</strong>"
                    ));
                }
                $this->setEntity($entity);
            }
        } catch (\Exception $ex) {
            if (isset($data[$this->getIdentifierName()])) {
                if ($data[$this->getIdentifierName()] == "")
                    $this->flashMessenger()->addMessage(array(
                        'error' => "<strong>" . $this->getTranslator()->translate('e_not_created') . "</strong> ->" . $ex->getMessage())
                    );
                else
                    $this->flashMessenger()->addMessage(array(
                        'error' => "<strong>" . $this->getTranslator()->translate('e_not_updated') . "</strong> ->" . $ex->getMessage())
                    );
            }
            $this->jsLog()->log($ex);
        }
        return false;
    }

    public function editarAction() {
        if ($this->getRequest()->isPost()) {
            $this->salvarAction();
            if ($this->getEntity()) {
                $this->initRoutesAction();
                $submitValue = $this->getFormEditar()->getElementSubmit()->getValue();
                $routes = $this->getRoutesAction();
                if (in_array($submitValue, array_keys($routes))) {
                    $action = $routes[$submitValue];
                    if (empty($action))
                        $this->flashMessenger()->addMessage(array(
                            'notice' => "<strong>Rota não implementada</strong>"
                        ));
                    else {
                        if (is_callable($routes[$submitValue]))
                            return $this->redirect()->toUrl($routes[$submitValue]($this));
                        else
                            return $this->redirect()->toUrl($routes[$submitValue]);
                    }
                } else {
                    $this->getFormEditar()->bind($this->getEntity());
                    $this->flashMessenger()->addMessage(array(
                        'notice' => "<strong>" . $this->getTranslator()->translate('n_not_redirect') . "</strong>"
                    ));
                }
            }
        } else {
            $codigo = $this->getEvent()->getRouteMatch()->getParam($this->getIdentifierName());
            if ($codigo != null) {
                try {
                    $registro = $this->getRepository()->find($codigo);
                    if ($registro == null)
                        throw new BaseException($this->getTranslator()->translate('e_entity_not_found', BaseException::ERROR_ENTITY_NOT_EXIST));
                    $this->getFormEditar()->bind($registro);
                } catch (\Exception $ex) {
                    $this->flashMessenger()->addMessage(array(
                        'error' => "<strong>" . $this->getTranslator()->translate('e_not_load_entity') . "</strong> ->" . $ex->getMessage()
                    ));
                    $this->jsLog()->log($ex);
                }
            }
        }
        $messages = $this->jsMessage()->messagesComplex($this->flashMessenger()->getCurrentMessages());
        $this->flashMessenger()->clearCurrentMessages();
        return new ViewModel(array(
            'messages' => $messages,
            'form' => $this->getFormEditar()
        ));
    }

    public function consultarAction() {
        $messages = $this->jsMessage()->messagesComplex($this->flashMessenger()->getCurrentMessages());
        $this->flashMessenger()->clearCurrentMessages();
        $form = $this->getFormConsultar();
        return new ViewModel(array(
            'form' => $form,
            'messages' => $messages
        ));
    }

    public function excluirAction() {
        if ($this->getRequest()->isPost()) {
            try {
                $codigos = $this->params()->fromPost($this->getIdentifierName() . 's', array());
                $codigos = count($codigos) == 0 ? $this->params()->fromPost($this->getIdentifierName(), array()) : $codigos;
                if (!is_array($codigos))
                    $codigos = array($codigos);
                foreach ($codigos as $codigo) {
                    $this->getService()->remove(array(
                        $this->getIdentifierName() => $codigo
                    ));
                }
            } catch (\Exception $ex) {
                $this->jsResponse()->error("<strong>" . $this->getTranslator()->translate('e_not_deleted') . "</strong> -> " . $ex->getMessage());
                $this->jsLog()->log($ex);
                return $this->getResponse();
            }
            return new \Zend\View\Model\JsonModel(array());
        }
    }

    public function buscarregistrosAction() {
        if ($this->getRequest()->isGet()) {
            $opcaoConsulta = $this->params()->fromQuery('opcoesConsulta', "");
            $firstResult = abs($this->params()->fromQuery('iDisplayStart', 0));
            $pageSize = abs($this->params()->fromQuery("iDisplayLength", $this->getPageSize()));
            $columnFlag = '';
            $repository = $this->getRepository();
            $orderBy = array_keys($repository::$orderByMap);
            array_unshift($orderBy, $columnFlag);
            try {
                $result = $this->find(array(
                    'opcoesConsulta' => $opcaoConsulta,
                    'firstResult' => $firstResult,
                    'pageSize' => $pageSize,
                    'orderBy' => $this->jsDataTable()->getOrderBy($orderBy)
                ));

                return new \Zend\View\Model\JsonModel(array(
                    "iTotalDisplayRecords" => $result['totalResult'],
                    'iTotalRecords' => $result['totalResult'],
                    'rows' => $result['result']
                ));
            } catch (\Exception $ex) {
                $this->jsResponse()->error("<strong>" . $this->getTranslator()->translate('e_not_find_entities') . "</strong> -> " . $ex->getMessage());
                $this->jsLog()->log($ex);
                return $this->getResponse();
            }
        }
    }

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

    public function getEntity() {
        return $this->entity;
    }

    public function setEntity($entity) {
        $this->entity = $entity;
        return $this;
    }

    public function getPageSize() {
        return $this->pageSize;
    }

    public function setPageSize($pageSize) {
        $this->pageSize = $pageSize;
        return $this;
    }

    public function getFormEditar() {
        return $this->formEditar;
    }

    public function setFormEditar($formEditar) {
        $this->formEditar = $formEditar;
        return $this;
    }

    public function getFormConsultar() {
        return $this->formConsultar;
    }

    public function setFormConsultar($formConsultar) {
        $this->formConsultar = $formConsultar;
        return $this;
    }

    public function getRepository() {
        return $this->repository;
    }

    public function setRepository($repository) {
        $this->repository = $repository;
        return $this;
    }

    public function getService() {
        return $this->service;
    }

    public function setService($service) {
        $this->service = $service;
        return $this;
    }

    public function getRoutesAction() {
        return $this->routesAction;
    }

    public function setRoutesAction($routesAction) {
        $this->routesAction = $routesAction;
        return $this;
    }

    public function getTranslator() {
        return $this->translator;
    }

    public function setTranslator($translator) {
        $this->translator = $translator;
        return $this;
    }

    public function getIdentifierName() {
        return $this->identifierName;
    }

    public function setIdentifierName($identifierName) {
        $this->identifierName = $identifierName;
        return $this;
    }

}
