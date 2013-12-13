<?php

namespace JS\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class BaseForm extends Form implements ObjectManagerAwareInterface {

    private $submitValue;
    private $btnSalvar;
    private $btnSalvarIncluir;
    private $btnSalvarConcluir;
    private $btnCancelar;
    private $btnExcluir;
    private $modelCodigo = 'codigo';
    private $fielsetButtons;
    protected $objectManager;

    public function __construct(ObjectManager $objectManager, $name = null, $options = array()) {
        parent::__construct($name, $options);

        $this->setObjectManager($objectManager);
        $this->setHydrator(new DoctrineObject($objectManager));
    }

    public function addFormActions() {

        $this->fielsetButtons = new Fieldset('formActions');

        $this->submitValue = new Element\Hidden('submitValue');
        $this->submitValue->setAttribute('data-ng-init', "submitValue = 'save'")
                //->setAttribute('data-ng-model', 'submitValue')
                ->setValue("{{ submitValue }}");

        $this->btnSalvar = new Element\Button("btnSalvar");
        $this->btnSalvar->setLabel("<span class='glyphicon glyphicon-floppy-disk'></span> Salvar")
                ->setAttribute('data-ng-click', "submitValue = 'save'")
                ->setAttribute('title', 'Salvar');

        $this->btnSalvarIncluir = new Element\Button("btnSalvarIncluir");
        $this->btnSalvarIncluir->setLabel("<span class='glyphicon glyphicon-pencil'></span> Salvar e Incluir")
                ->setAttribute('data-ng-click', "submitValue = 'save_and_new'")
                ->setAttribute('title', 'Salvar e Incluir um Novo');

        $this->btnSalvarConcluir = new Element\Button("btnSalvarConcluir");
        $this->btnSalvarConcluir->setLabel("<span class='glyphicon glyphicon-new-window'></span> Salvar e Concluir")
                ->setAttribute('data-ng-click', "submitValue = 'save_and_close'")
                ->setAttribute('title', "Salvar e Concluir");

        $this->btnCancelar = new Element\Button("btnCancelar");
        $this->btnCancelar->setLabel("<span class='glyphicon glyphicon-floppy-remove'></span>")
                ->setValue("cancelar")
                ->setAttribute('title', "Cancelar")
                ->setAttribute('class', 'btn btn-default');

        $this->btnExcluir = new Element\Button("btnExcluir");
        $this->btnExcluir->setLabel("<span class='glyphicon glyphicon-trash'></span>")
                ->setAttribute('data-ng-delete', "optionsDelete")
                ->setAttribute('data-ng-disabled', $this->getModelCodigo() . " == ''")
                ->setAttribute('title', "Excluir")
                ->setAttribute('class', 'btn btn-danger');

        $this->fielsetButtons->add($this->submitValue);
        $this->fielsetButtons->add($this->btnSalvarConcluir);
        $this->fielsetButtons->add($this->btnSalvar);
        $this->fielsetButtons->add($this->btnSalvarIncluir);
        $this->fielsetButtons->add($this->btnCancelar);
        $this->fielsetButtons->add($this->btnExcluir);
        $this->add($this->fielsetButtons);
    }

    /**
     * @return \Zend\Form\Fieldset
     */
    public function getFielsetButtons() {
        return $this->fielsetButtons;
    }

    public function setFielsetButtons($fielsetButtons) {
        $this->fielsetButtons = $fielsetButtons;
    }

    public function getElementSubmit() {
        return $this->getSubmitValue();
    }

    /**
     * @return \Zend\Form\Element\Hidden
     */
    public function getSubmitValue() {
        return $this->submitValue;
    }

    public function setSubmitValue($submitValue) {
        $this->submitValue = $submitValue;
    }

    /**
     * @return \Zend\Form\Element\Button
     */
    public function getBtnSalvar() {
        return $this->btnSalvar;
    }

    public function setBtnSalvar($btnSalvar) {
        $this->btnSalvar = $btnSalvar;
    }

    /**
     * @return \Zend\Form\Element\Button
     */
    public function getBtnSalvarIncluir() {
        return $this->btnSalvarIncluir;
    }

    public function setBtnSalvarIncluir($btnSalvarIncluir) {
        $this->btnSalvarIncluir = $btnSalvarIncluir;
    }

    /**
     * @return \Zend\Form\Element\Button
     */
    public function getBtnSalvarConcluir() {
        return $this->btnSalvarConcluir;
    }

    public function setBtnSalvarConcluir($btnSalvarConcluir) {
        $this->btnSalvarConcluir = $btnSalvarConcluir;
    }

    /**
     * @return \Zend\Form\Element\Button
     */
    public function getBtnCancelar() {
        return $this->btnCancelar;
    }

    public function setBtnCancelar($btnCancelar) {
        $this->btnCancelar = $btnCancelar;
    }

    /**
     * @return \Zend\Form\Element\Button
     */
    public function getBtnExcluir() {
        return $this->btnExcluir;
    }

    public function setBtnExcluir($btnExcluir) {
        $this->btnExcluir = $btnExcluir;
    }

    public function getModelCodigo() {
        return $this->modelCodigo;
    }

    public function setModelCodigo($modelCodigo) {
        $this->modelCodigo = $modelCodigo;
    }

    public function getObjectManager() {
        return $this->objectManager;
    }

    public function setObjectManager(ObjectManager $objectManager) {
        $this->objectManager = $objectManager;
    }

}
