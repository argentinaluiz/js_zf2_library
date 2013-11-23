<?php

namespace JS\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

abstract class MultiPageForm extends Form implements MultiPageFormInterface, ServiceLocatorAwareInterface {

    protected $serviceLocator;

    public function __construct($sm, $name = null, $options = array()) {
        $this->setServiceLocator($sm);
        parent::__construct($name, $options);
    }

    public function addCancelButton(Form $subForm) {
        $subForm->remove("btnCancelar");
        $btnCancelar = new Element\Button("btnCancelar");
        $btnCancelar->setLabel("Cancelar")->
                setAttribute('class', 'btn')
                ->setAttribute('type', 'button');
        $subForm->add($btnCancelar);
        return $this;
    }

    public function addFinishButton(Form $subForm) {
        $subForm->remove("btnSalvar");
        $btnSalvar = new Element\Button("btnSalvar");
        $btnSalvar->setLabel("Finalizar")->
                setAttribute('class', 'btn btn-primary')
                ->setAttribute('type', 'submit');
        $subForm->add($btnSalvar);
        return $this;
    }

    public function addSubmitButton(Form $subForm) {
        $subForm->remove("btnSalvar");
        $btnSalvar = new Element\Button("btnSalvar");
        $btnSalvar->setLabel("Salvar e Continuar")->
                setAttribute('class', 'btn btn-primary')
                ->setAttribute('type', 'submit');
        $subForm->add($btnSalvar);
        return $this;
    }

    public function prepareSubForm(Form $form, $isLastForm = false) {
        if ($form instanceof Form)
            $subForm = $form;
        else
            throw new \Exception('Argumento Inválido Passado para ' . __FUNCTION__ . '()');
        if (!$isLastForm)
            $this->addSubmitButton($subForm);
        else
            $this->addFinishButton($subForm);
        $this->addCancelButton($subForm);
        return $subForm;
    }

    public function getData($flag = \Zend\Form\FormInterface::VALUES_NORMALIZED) {
        $data = parent::getData($flag);
        $arrayWrap = array_values(array_slice($data, -(count($this->getFieldsets()))));
        array_splice($data, -(count($this->getFieldsets())));
        foreach ($arrayWrap as $key => $value)
            $data+=$value;
        return $data;
    }

    public function getForms() {
        return $this->forms;
    }

    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public function getFormManager() {
        return $this->serviceLocator->get('FormElementManager');
    }

}
