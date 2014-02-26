<?php

namespace JS\Form;

use Zend\Form\Fieldset;

class ActionsFieldset extends Fieldset {

    private $modelCodigo = 'codigo';

    public function __construct($name = 'formActions', $options = array()) {
        parent::__construct($name, $options);

        $this->setModelCodigo(isset($this->options['model-codigo']) ? $this->options['model-codigo'] : 'codigo');

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
                'class' => 'btn btn-primary',
                'data-ng-click' => "submitValue = 'save_and_close'",
                'title' => 'Salvar e Concluir',
                'type' => 'submit'
            ),
            'options' => array(
                'label' => "Salvar e Concluir",
                'glyphicon' => 'new-window',
                'dropdown' => array(
                    'split' => true,
                    'dropup' => true,
                    'items' => array(
                        array(
                            'label' => 'Salvar',
                            'item_attributes' => array(
                                'data-ng-click' => "submitValue = 'save'",
                                'title' => 'Salvar',
                                'data-ng-triggersubmit' => ''
                            )
                        ),
                        array(
                            'label' => 'Salvar e Incluir',
                            'item_attributes' => array(
                                'data-ng-click' => "submitValue = 'save_and_new'",
                                'title' => 'Salvar e Incluir um Novo',
                                'data-ng-triggersubmit' => ''
                            )
                        ),
                    )
                )
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
                'label' => "",
                'glyphicon' => 'floppy-remove'
            ),
        ));

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
                'label' => "",
                'glyphicon' => 'trash'
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
