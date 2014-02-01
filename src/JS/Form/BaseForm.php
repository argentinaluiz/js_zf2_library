<?php

namespace JS\Form;

use JS\Form\FormActionsFieldset;
use Doctrine\Common\Persistence\ObjectManager;

class BaseForm extends AbstractForm {

    private $modelCodigo = 'codigo';
    private $fielsetButtons;

    public function __construct(ObjectManager $objectManager, $name = null, $options = array()) {
        parent::__construct($objectManager, $name, $options);
    }

    public function addFormActions($options = array()) {
        $this->fielsetButtons = new FormActionsFieldset('formActions', array('model-codigo' => $this->modelCodigo));
        $this->add($this->fielsetButtons);
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
    public function getFielsetButtons() {
        return $this->fielsetButtons;
    }

    public function setFielsetButtons($fielsetButtons) {
        $this->fielsetButtons = $fielsetButtons;
    }

}
