<?php

namespace JS\Controller;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use JS\Controller\MultiPageControllerInteface;
use Zend\Session;
use Zend\View\Model\ViewModel;
use Zend\Form\FormInterface;

abstract class MultiPageController extends RoutesActionController implements MultiPageControllerInteface {

    /**
     * @var \JS\Form\MultiPageForm
     */
    protected $multiForm;

    /**
     * @var \Zend\Session\Container
     */
    protected $container;
    protected $namespace;
    private $entity = null;
    private $entityName;
    private $service;
    private $translator;
    private $hydrator;

    /**
     * Verifica se todos formularios sao validos
     * @return boolean
     */
    public function formIsValid() {
        $data = array();
        foreach ($this->getContainer() as $key => $info) {
            $data[$key] = $info;
        }
        $this->getMultiForm()->setData($data);
        return $this->getMultiForm()->isValid();
    }

    /**
     * Pega o atual formulario na tela do usuario.
     * Utiliza post
     * @return mixed \Zend\Form\Form | boolean
     */
    public function getCurrentSubForm() {
        if (!$this->getRequest()->isPost()) {
            return false;
        }
        foreach ($this->getPotentialForms() as $name) {
            if ($data = $this->params()->fromPost($name, false)) {
                if (is_array($data)) {
                    return $this->getMultiForm()->get($name);
                }
            }
        }
        return false;
    }

    /**
     * Pega o proximo formulario
     * @return mixed \Zend\Form\Form | boolean
     */
    public function getNextSubForm() {
        $savedForms = $this->getSavedForms();
        $potentialForms = $this->getPotentialForms();

        foreach ($potentialForms as $name)
            if (!in_array($name, $savedForms))
                return $this->getMultiForm()->get($name);

        return false;
    }

    /**
     * Pega os formularios inseridos no formulario geral
     * @return array
     */
    public function getPotentialForms() {
        $array = array_keys($this->getMultiForm()->getFieldsets());
        if (count($array) == 0)
            throw new Exception("Nenhum sub-formulario encontrado.");
        return $array;
    }

    /**
     * Pega os formularios incluidos no container
     * @return array
     */
    public function getSavedForms() {
        $savedForms = array();
        foreach ($this->getContainer() as $key => $value)
            $savedForms[] = $key;

        return $savedForms;
    }

    /*
     * @return \Zend\Form\Form
      public function getLastForm() {
      $nameLastForm = end($this->getPotentialForms());
      $lastForm = $this->getForm()->get($nameLastForm);
      $lastForm->setData($this->getContainer()->$nameLastForm);
      return $lastForm;
      } */

    /**
     * Verifica se o formulario e o ultimo
     * @return boolean
     */
    public function subFormIsLast(\Zend\Form\Form $subForm) {
        $nameLastForm = end($this->getPotentialForms());
        $lastForm = $this->getMultiForm()->get($nameLastForm);
        return get_class($subForm) == get_class($lastForm);
    }

    /**
     * Verifica se o formulario e valido
     * @return boolean
     */
    public function subFormIsValid(\Zend\Form\Form $subForm, array $data) {
        $name = $subForm->getName();

        if (isset($data[$name]))
            $subForm->setData($data[$name]);
        else
            $subForm->setData(array());

        if ($subForm->isValid()) {
            $this->getContainer()->$name = $subForm->getData(FormInterface::VALUES_AS_ARRAY);
            return true;
        }

        return false;
    }

    /**
     * @param \Zend\Form\Form $form
     * @return ViewModel $viewModel
     */
    abstract protected function renderTemplate($form);

    private function renderStep($form) {
        $form = $this->getMultiForm()->prepareSubForm($form, $this->subFormIsLast($form));
        $viewModel = $this->renderTemplate($form);
        if ($viewModel instanceof ViewModel) {
            $messages = $this->jsMessage()->messages($this->flashMessenger()->getCurrentMessages());
            $this->flashMessenger()->clearCurrentMessages();
            $viewModel->setVariable('messages', $messages);
        }
        return $viewModel;
    }

    private function create() {
        $entity = $this->hydrateFormData();
        try {
            $entity = $this->getService()->create($entity);
            $this->flashMessenger()->addMessage(array(
                'info' => "<strong>" . $this->getTranslator()->translate('s_created') . "</strong>"
            ));
            $this->setEntity($entity);
        } catch (\Exception $ex) {
            $this->flashMessenger()->addMessage(array(
                'error' => "<strong>" . $this->getTranslator()->translate('e_not_created') . "</strong> ->" . $ex->getMessage())
            );
            $this->jsLog()->log($ex);
        }
        return false;
    }

    /**
     * @return Object Entity Hydrated
     */
    public function hydrateFormData() {
        $entityName = $this->getEntityName();
        $entity = new $entityName;
        $entity = $this->getHydrator()->hydrate($this->getMultiForm()->getData(), $entity);
        return $entity;
    }

    public function novoAction() {
        $this->destroyContainer();
        $form = $this->getNextSubForm();
        return $this->renderStep($form);
    }

    public function processAction() {
        $form = $this->getCurrentSubForm();
        if (!$form) {
            $this->destroyContainer();
            return $this->redirect()->toRoute(null, array('action' => 'novo'));
        }

        $data = array_merge_recursive($this->params()->fromFiles(), $this->params()->fromPost());
        if (!$this->subFormIsValid($form, $data))
            return $this->renderStep($form);

        $formNext = $this->getNextSubForm();
        if ($formNext)
            return $this->renderStep($formNext);

        if (!$this->formIsValid())
            return $this->renderStep($form);

        $this->create();
        if ($this->getEntity()) {
            $data = $this->params()->fromPost();
            $result = $this->triggerRoutesAction($data[$form->getName()]['formActions']['submitValue']);
            return $result ? $result : $this->redirect()->toRoute(null, array('action' => 'novo'));
        } else
            return $this->renderStep($form);
    }

    /**
     * Destroi o container
     */
    public function destroyContainer() {
        $this->getContainer()->getManager()->getStorage()->clear($this->getNamespace());
        $this->container = null;
    }

    public function setMultiForm($form) {
        $this->multiForm = $form;
        return $this;
    }

    public function getMultiForm() {
        return $this->multiForm;
    }

    /**
     * Pega o container,
     * Se nao existe container, cria com namespace
     * do controller atual
     * @return \Zend\Session\Container
     */
    public function getContainer() {
        if (null === $this->container) {
            $this->container = new Session\Container($this->getNamespace());
        }
        return $this->container;
    }

    public function getNamespace() {
        if (!$this->namespace)
            throw new \Exception("Namespace não definido");
        return $this->namespace;
    }

    public function setNamespace($name) {
        $this->namespace = $name;
        return $this;
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

    public function getHydrator() {
        if (!$this->hydrator)
            $this->hydrator = new DoctrineObject($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
        return $this->hydrator;
    }

    public function setHydrator($hydrator) {
        $this->hydrator = $hydrator;
    }

}
