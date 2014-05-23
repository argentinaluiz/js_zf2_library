<?php

namespace JS\Controller;

use Zend\View\Model\ViewModel;
use JS\Exception\BaseException;
use JS\Service\BaseServiceInterface;
use JSDataTables\Service\JSDataTables;

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
    private $formConsultar;
    private $formCreate;
    private $formUpdate;
    private $service;
    private $dataTables;
    private $translator;

    /**
     * @param \Zend\Form\Form $form
     */
    public function updateOrCreate($form) {
        $checkEntityNotExist = false;
        $formName = $form->getName();
        $data = $this->params()->fromPost();
        if (!empty($formName) && $form->wrapElements()) {
            $data = $data[$formName];
        }
        $codigo = $this->getIdentifierData($form, $data);
        if ($codigo) {
            $entity = $this->getService()->find($codigo);
            if (!$entity) {
                $checkEntityNotExist = true;
            } else {
                $form->bind($entity);
            }
        } else {
            $checkEntityNotExist = true;
        }
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
        if (!empty($formName) && $form->wrapElements()) {
            $data = $data[$formName];
        }
        $codigo = $this->getIdentifierData($form, $data);
        if ($codigo) {
            $entity = $this->getService()->find($codigo);
            if (!$entity) {
                throw new BaseException($this->getTranslator()->translate('e_entity_not_found'), BaseException::ERROR_ENTITY_NOT_EXIST);
            }
        } else {
            throw new BaseException($this->getTranslator()->translate('e_entity_not_found'), BaseException::ERROR_ENTITY_NOT_EXIST);
        }
        $form->bind($entity);
        $form->setData($data);

        if ($form->isValid()) {
            $entity = $this->getService()->update($entity);
            $this->setEntity($entity);
            return true;
        }
        return false;
    }

    public function create($form) {
        $formName = $form->getName();
        $data = $this->params()->fromPost();
        if (!empty($formName) && $form->wrapElements()) {
            $data = $data[$formName];
        }
        $form->setData($data);
        if ($form->isValid()) {
            $entity = $this->getService()->create($form->getObject());
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
                if ($this->create($this->getFormCreate())) {
                    $this->flashMessenger()->addMessage([
                        'info' => "<strong>" . $this->getTranslator()->translate('s_created') . "</strong>"
                    ]);
                }
            } catch (\Exception $ex) {
                $this->flashMessenger()->addMessage([
                    'error' => "<strong>" . $this->getTranslator()->translate('e_not_created') . "</strong> ->" . $ex->getMessage()
                ]);
                $this->jsLog()->log($ex);
            }
            if ($this->getEntity()) {
                $result = $this->triggerRoutesAction($this->getFormCreate()->get('formActions')->getSubmitValue()->getValue());
                if ($result) {
                    return $result;
                } else {
                    $className = get_class($this->getFormCreate()->getObject());
                    $this->getFormCreate()->bind(new $className);
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
            $registro = $this->getService()->find($codigo);
            if ($registro == null) {
                throw new BaseException($this->getTranslator()->translate('e_entity_not_found'), BaseException::ERROR_ENTITY_NOT_EXIST);
            }
            $form->bind($registro);
        } else {
            throw new BaseException($this->getTranslator()->translate('e_entity_not_found'), BaseException::ERROR_ENTITY_NOT_EXIST);
        }
    }

    public function editarAction() {
        if ($this->getRequest()->isPost()) {
            try {
                $result = $this->updateOrCreate($this->getFormUpdate());
                if ($result) {
                    if ($result == 1) {
                        $this->flashMessenger()->addMessage([
                            'info' => "<strong>" . $this->getTranslator()->translate('s_updated') . "</strong>"
                        ]);
                    } else {
                        $this->flashMessenger()->addMessage([
                            'info' => "<strong>" . $this->getTranslator()->translate('s_created') . "</strong>"
                        ]);
                    }
                }
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

    public function consultarAction() {
        $messages = $this->jsMessage()->messagesComplex($this->flashMessenger()->getCurrentMessages());
        $this->flashMessenger()->clearCurrentMessages();
        $form = $this->getFormConsultar();
        return new ViewModel([ 'form' => $form, 'messages' => $messages]);
    }

    public function buscarregistrosAction() {
        if ($this->getRequest()->isGet()) {
            try {
                return new \Zend\View\Model\JsonModel($this->getDataTables()->getPaginator());
            } catch (\Exception $ex) {
                $this->jsResponse()->error("<strong>" . $this->getTranslator()->translate('e_not_find_entities') . "</strong> -> " . $ex->getMessage());
                $this->jsLog()->log($ex);
                return $this->getResponse();
            }
        }
    }

    protected function getEntity() {
        return $this->entity;
    }

    protected function setEntity($entity) {
        $this->entity = $entity;
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

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository() {
        return $this->getService()->getRepository();
    }

    /**
     * @return \JS\Service\BaseServiceInterface
     */
    public function getService() {
        return $this->service;
    }

    /**
     * @param \JS\Service\BaseServiceInterface $service
     */
    public function setService(BaseServiceInterface $service) {
        $this->service = $service;
        return $this;
    }

    /**
     * @return \JSDataTables\Service\JSDataTables
     */
    public function getDataTables() {
        return $this->dataTables;
    }

    /**
     * @param \JSDataTables\Service\JSDataTables $dataTable
     */
    public function setDataTables(JSDataTables $dataTable) {
        $this->dataTables = $dataTable;
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
