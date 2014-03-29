<?php

namespace JS\Controller;

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
 * @method \JS\Plugin\JSResponse jsResponse
 * @method \JS\Plugin\JSMessage jsMessage
 * @method \JS\Plugin\Log jsLog
 */
abstract class BaseController extends RoutesActionController {

    private $entity = null;
    private $entityName;
    private $formConsultar;
    private $formCreate;
    private $formUpdate;
    private $pageSize = 20;
    private $repository;
    private $service;
    private $translator;

    abstract public function find($opcoes);

    /**
     * @param \Zend\Form\Form $form
     */
    public function updateOrCreate($form, $entityName) {
        $checkEntityNotExist = false;
        $formName = $form->getName();
        $data = $this->params()->fromPost();
        if (!empty($formName) && $form->wrapElements())
            $data = $data[$formName];
        $codigo = $this->getIdentifierData($form, $data);
        if ($codigo) {
            $entity = $this->getRepository()->find($codigo);
            if (!$entity) {
                $entity = new $entityName;
                $checkEntityNotExist = true;
            }
        } else {
            $entity = new $entityName;
            $checkEntityNotExist = true;
        }
        $form->bind($entity);
        $form->setData($data);

        if ($form->isValid()) {
            if (!$checkEntityNotExist) {
                $entity = $this->getService()->update($entity);
                $this->setEntity($entity);
                return 1;
            } else {
                if ($this->getFormCreate()) {
                    $entity = $this->getService()->create($entity);
                    $this->setEntity($entity);
                    return 2;
                }
                throw new BaseException($this->getTranslator()->translate('e_entity_not_found'), BaseException::ERROR_ENTITY_NOT_EXIST);
            }
        }
        return false;
    }

    public function update($form) {
        $formName = $form->getName();
        $data = $this->params()->fromPost();
        if (!empty($formName) && $form->wrapElements())
            $data = $data[$formName];
        $codigo = $this->getIdentifierData($form, $data);
        if ($codigo) {
            $entity = $this->getRepository()->find($codigo);
            if (!$entity)
                throw new BaseException($this->getTranslator()->translate('e_entity_not_found'), BaseException::ERROR_ENTITY_NOT_EXIST);
        } else
            throw new BaseException($this->getTranslator()->translate('e_entity_not_found'), BaseException::ERROR_ENTITY_NOT_EXIST);
        $form->bind($entity);
        $form->setData($data);

        if ($form->isValid()) {
            $entity = $this->getService()->update($entity);
            $this->setEntity($entity);
            return true;
        }
        return false;
    }

    public function create($form, $entityName) {
        $formName = $form->getName();
        $data = $this->params()->fromPost();
        if (!empty($formName) && $form->wrapElements())
            $data = $data[$formName];
        $entity = new $entityName;
        $form->bind($entity);
        $form->setData($data);
        if ($form->isValid()) {
            $entity = $this->getService()->create($entity);
            $this->setEntity($entity);
            return true;
        }
        return false;
    }

    public function indexAction() {
        return $this->redirect()->toRoute(null, ['action' => 'consultar']);
    }

    public function novoAction() {
        if ($this->getRequest()->isPost()) {
            try {
                if ($this->create($this->getFormCreate(), $this->getEntityName()))
                    $this->flashMessenger()->addMessage([
                        'info' => "<strong>" . $this->getTranslator()->translate('s_created') . "</strong>"
                    ]);
            } catch (\Exception $ex) {
                $this->flashMessenger()->addMessage([
                    'error' => "<strong>" . $this->getTranslator()->translate('e_not_created') . "</strong> ->" . $ex->getMessage()
                ]);
                $this->jsLog()->log($ex);
            }
            if ($this->getEntity()) {
                $result = $this->triggerRoutesAction($this->getFormCreate()->get('formActions')->getSubmitValue()->getValue());
                if ($result)
                    return $result;
                else {
                    $entityName = $this->getEntityName();
                    $this->getFormCreate()->bind(new $entityName);
                }
            }
        }
        $messages = $this->jsMessage()->messagesComplex($this->flashMessenger()->getCurrentMessages());
        $this->flashMessenger()->clearCurrentMessages();
        return new ViewModel([ 'messages' => $messages, 'form' => $this->getFormCreate()]);
    }

    public function loadEntityToForm($form) {
        $codigo = $this->getEvent()->getRouteMatch()->getParam($this->getIdentifierName());
        if ($codigo != null) {
            $registro = $this->getRepository()->find($codigo);
            if ($registro == null)
                throw new BaseException($this->getTranslator()->translate('e_entity_not_found', BaseException::ERROR_ENTITY_NOT_EXIST));
            $form->bind($registro);
        } else
            throw new BaseException($this->getTranslator()->translate('e_entity_not_found', BaseException::ERROR_ENTITY_NOT_EXIST));
    }

    public function editarAction() {
        if ($this->getRequest()->isPost()) {
            try {
                $result = $this->updateOrCreate($this->getFormUpdate(), $this->getEntityName());
                if ($result)
                    if ($result == 1)
                        $this->flashMessenger()->addMessage([
                            'info' => "<strong>" . $this->getTranslator()->translate('s_updated') . "</strong>"
                        ]);
                    else
                        $this->flashMessenger()->addMessage([
                            'info' => "<strong>" . $this->getTranslator()->translate('s_created') . "</strong>"
                        ]);
            } catch (\Exception $ex) {
                $this->flashMessenger()->addMessage([
                    'error' => "<strong>" . $this->getTranslator()->translate('e_not_updated') . "</strong> ->" . $ex->getMessage()
                ]);
                $this->jsLog()->log($ex);
            }
            if ($this->getEntity()) {
                $result = $this->triggerRoutesAction($this->getFormUpdate()->get('formActions')->getSubmitValue()->getValue());
                return $result ? $result : $this->redirect()->toRoute($this->getRoute(), [
                            'action' => 'editar',
                            $this->getIdentifierName() => $this->getEntity()->{'get' . ucfirst($this->getIdentifierName())}()
                ]);
            }
        } else {
            try {
                $this->loadEntityToForm($this->getFormUpdate());
            } catch (\Exception $ex) {
                $this->flashMessenger()->addMessage([
                    'error' => "<strong>" . $this->getTranslator()->translate('e_not_load_entity') . "</strong> ->" . $ex->getMessage()
                ]);
                $this->jsLog()->log($ex);
                return $this->redirect()->toRoute($this->getRoute(), ['action' => 'novo']);
            }
        }
        $messages = $this->jsMessage()->messagesComplex($this->flashMessenger()->getCurrentMessages());
        $this->flashMessenger()->clearCurrentMessages();

        return new ViewModel([ 'messages' => $messages, 'form' => $this->getFormUpdate()]);
    }

    public function consultarAction() {
        $messages = $this->jsMessage()->messagesComplex($this->flashMessenger()->getCurrentMessages());
        $this->flashMessenger()->clearCurrentMessages();
        $form = $this->getFormConsultar();
        return new ViewModel([ 'form' => $form, 'messages' => $messages]);
    }

    public function excluirAction() {
        if ($this->getRequest()->isPost()) {
            try {
                $codigos = $this->params()->fromPost($this->getIdentifierName() . 's', []);
                $codigos = count($codigos) == 0 ? $this->params()->fromPost($this->getIdentifierName(), []) : $codigos;
                if (!is_array($codigos))
                    $codigos = [$codigos];
                foreach ($codigos as $codigo) {
                    $this->getService()->remove($codigo);
                }
            } catch (\Exception $ex) {
                $this->jsResponse()->error("<strong>" . $this->getTranslator()->translate('e_not_deleted') . "</strong> -> " . $ex->getMessage());
                $this->jsLog()->log($ex);
                return $this->getResponse();
            }
            return new \Zend\View\Model\JsonModel([]);
        }
    }

    public function buscarregistrosAction() {
        if ($this->getRequest()->isGet()) {
            $opcaoConsulta = $this->params()->fromQuery('opcoesConsulta', "");
            $firstResult = abs($this->params()->fromQuery('iDisplayStart', 0));
            $pageSize = abs($this->params()->fromQuery("iDisplayLength", $this->getPageSize()));
            $columnFlag = '';
            $repository = $this->getRepository();
            $orderBy = array_keys($repository->orderByMap);
            array_unshift($orderBy, $columnFlag);
            try {
                $result = $this->find([
                    'opcoesConsulta' => $opcaoConsulta,
                    'firstResult' => $firstResult,
                    'pageSize' => $pageSize,
                    'orderBy' => $this->jsDataTable()->getOrderBy($orderBy)
                ]);

                return new \Zend\View\Model\JsonModel([
                    "iTotalDisplayRecords" => $result['totalResult'],
                    'iTotalRecords' => $result['totalResult'],
                    'rows' => $result['result']
                ]);
            } catch (\Exception $ex) {
                $this->jsResponse()->error("<strong>" . $this->getTranslator()->translate('e_not_find_entities') . "</strong> -> " . $ex->getMessage());
                $this->jsLog()->log($ex);
                return $this->getResponse();
            }
        }
    }

    public function getEntity() {
        return $this->entity;
    }

    public function setEntity($entity) {
        $this->entity = $entity;
        return $this;
    }

    public function getEntityName() {
        return $this->entityName;
    }

    public function setEntityName($entityName) {
        $this->entityName = $entityName;
        return $this;
    }

    public function getPageSize() {
        return $this->pageSize;
    }

    public function setPageSize($pageSize) {
        $this->pageSize = $pageSize;
        return $this;
    }

    public function getFormCreate() {
        return $this->formCreate;
    }

    public function setFormCreate($formCreate) {
        $this->formCreate = $formCreate;
        return $this;
    }

    public function getFormUpdate() {
        return $this->formUpdate;
    }

    public function setFormUpdate($formUpdate) {
        $this->formUpdate = $formUpdate;
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

    public function getTranslator() {
        return $this->translator;
    }

    public function setTranslator($translator) {
        $this->translator = $translator;
        return $this;
    }

}
