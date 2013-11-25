<?php

namespace JS\Controller;

use JS\Controller\BaseController;
use JS\Controller\MultiPageControllerInteface;
use Zend\Session;

abstract class MultiPageController extends BaseController implements MultiPageControllerInteface {

    /**
     * @var \Zend\Form\Form
     */
    protected $multiForm;

    /**
     * @var \Zend\Session\Container
     */
    protected $container;
    protected $namespace;

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

    /**
     * Pega o atual formulario na tela do usuario.
     * Utiliza post
     * @return mixed \Zend\Form\Form | boolean
     */
    public function getCurrentSubForm() {
        if (!$this->getRequest()->isPost())
            return false;

        foreach ($this->getPotentialForms() as $name)
            if ($data = $this->params()->fromPost($name, false))
                if (is_array($data))
                    return $this->getMultiForm()->get($name);

        return false;
    }

    /**
     * Pega o formulario geral
     * @return \Zend\Form\Form
     */
    public function getMultiForm() {
        return $this->multiForm;
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

    public function getNamespace() {
        if (!$this->namespace)
            throw new \Exception("Namespace não definido");
        return $this->namespace;
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
            $this->getContainer()->$name = $subForm->getData();
            return true;
        }

        return false;
    }

    /**
     * Destroi o container
     */
    public function destroyContainer() {
        $this->getContainer()->getManager()->getStorage()->clear($this->getNamespace());
        $this->container = null;
    }

    public function setNamespace($name) {
        $this->namespace = $name;
    }

}
