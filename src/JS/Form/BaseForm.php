<?php

namespace JS\Form;

use JS\Form\ActionsFieldset;
use Doctrine\Common\Persistence\ObjectManager;

class BaseForm extends AbstractForm {

    private $modelCodigo = 'codigo';
    private $actions;

    public function __construct(ObjectManager $objectManager, $name = null, $options = array()) {
        parent::__construct($objectManager, $name, $options);
    }

    public function addActions($options = array()) {
        $this->actions = new ActionsFieldset('formActions', array('model-codigo' => $this->modelCodigo), $options);
        $this->add($this->actions);
    }

    public function getModelCodigo() {
        return $this->modelCodigo;
    }

    public function setModelCodigo($modelCodigo) {
        $this->modelCodigo = $modelCodigo;
    }

    /**
     * @return \Zend\Form\Fieldset
     */
    public function getActions() {
        return $this->actions;
    }

    public function setActions($fielsetButtons) {
        $this->actions = $fielsetButtons;
    }

}
