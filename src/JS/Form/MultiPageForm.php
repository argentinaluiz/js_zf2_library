<?php

namespace JS\Form;

use Zend\Form\Form;
use JS\Form\AbstractForm;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class MultiPageForm extends AbstractForm implements MultiPageFormInterface, ServiceLocatorAwareInterface {

    protected $serviceLocator;

    public function __construct($serviceLocator, $name = null, $options = array()) {
        parent::__construct($serviceLocator->get('Doctrine\ORM\EntityManager'), $name, $options);
        $this->setServiceLocator($serviceLocator);
    }

    public function addCancelButton(Form $subForm) {
        $subForm->remove("btnCancelar");
        $subForm->add(array(
            'type' => 'Button',
            'name' => 'Cancelar',
            'attributes' => array(
                'class' => 'btn btn-default',
                'type' => 'button'
            ),
            'options' => array(
                'label' => 'Cancelar'
            )
        ));
        return $this;
    }

    public function addSubmitButton(Form $subForm) {
        $subForm->remove("btnSalvar");
        $subForm->add(array(
            'type' => 'Button',
            'name' => 'btnSalvar',
            'attributes' => array(
                'class' => 'btn btn-default',
                'type' => 'button'
            ),
            'options' => array(
                'label' => 'Salvar e Continuar'
            )
        ));
        return $this;
    }

    public function prepareSubForm(Form $subForm, $isLastForm = false) {
        if (!$isLastForm) {
            $this->addSubmitButton($subForm);
            $this->addCancelButton($subForm);
        } else
            $subForm->add(new ActionsFieldset());
        return $subForm;
    }

    public function getData($flag = \Zend\Form\FormInterface::VALUES_AS_ARRAY) {
        $data = array();
        $forms = $this->getFieldsets();
        foreach ($forms as $form) {
            $array = array_values(array_slice($form->getData($flag), -1));
            $data+= $array[0];
        }
        return $data;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public function getFormManager() {
        return $this->serviceLocator->get('FormElementManager');
    }

}
