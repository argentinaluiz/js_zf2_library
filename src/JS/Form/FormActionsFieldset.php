<?php

namespace JS\Form;

use Zend\Form\Fieldset;

class FormActionsFieldset extends Fieldset {

    private $modelCodigo = 'codigo';

    public function __construct($name = 'formActions', $options = array()) {
        parent::__construct($name, $options);

        $this->add(array(
            'type' => 'Hidden',
            'name' => 'submitValue',
            'attributes' => array(
                'data-ng-init' => "submitValue = 'save'",
                'value' => '{{ submitValue }}'
            )
        ));

        $this->add(array(
            'type' => 'Button',
            'name' => 'btnSalvar',
            'attributes' => array(
                'data-ng-click' => "submitValue = 'save'",
                'title' => 'Salvar',
            ),
            'options' => array(
                'label' => "<span class='glyphicon glyphicon-floppy-disk'></span> Salvar"
            )
        ));

        $this->add(array(
            'type' => 'Button',
            'name' => 'btnSalvarIncluir',
            'attributes' => array(
                'data-ng-click' => "submitValue = 'save_and_new'",
                'title' => 'Salvar e Incluir um Novo',
            ),
            'options' => array(
                'label' => "<span class='glyphicon glyphicon-pencil'></span> Salvar e Incluir"
            )
        ));

        $this->add(array(
            'type' => 'Button',
            'name' => 'btnSalvarConcluir',
            'attributes' => array(
                'data-ng-click' => "submitValue = 'save_and_close'",
                'title' => 'Salvar e Concluir',
            ),
            'options' => array(
                'label' => "<span class='glyphicon glyphicon-new-window'></span> Salvar e Concluir"
            )
        ));

        $this->add(array(
            'type' => 'Button',
            'name' => 'btnCancelar',
            'attributes' => array(
                'class' => 'btn btn-default',
                'title' => 'Cancelar',
            ),
            'options' => array(
                'label' => "<span class='glyphicon glyphicon-floppy-remove'></span>"
            )
        ));


        $this->setModelCodigo(isset($this->options['model-codigo']) ? $this->options['model-codigo'] : 'codigo');
        $this->add(array(
            'type' => 'Button',
            'name' => 'btnExcluir',
            'attributes' => array(
                'class' => 'btn btn-danger',
                'title' => 'Excluir',
                'data-ng-delete' => "optionsDelete",
                'data-ng-disabled' => $this->getModelCodigo() . " == ''"
            ),
            'options' => array(
                'label' => "<span class='glyphicon glyphicon-trash'></span>"
            )
        ));
    }

    public function getModelCodigo() {
        return $this->modelCodigo;
    }

    public function setModelCodigo($modelCodigo) {
        $this->modelCodigo = $modelCodigo;
        return $this;
    }

    /**
     * @return \Zend\Form\Element\Hidden
     */
    public function getSubmitValue() {
        return $this->get('submitValue');
    }

}
