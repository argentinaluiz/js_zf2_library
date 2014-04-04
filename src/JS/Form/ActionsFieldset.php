<?php

namespace JS\Form;

use Zend\Form\Fieldset;

class ActionsFieldset extends Fieldset {

    private $modelCodigo = 'codigo';

    public function __construct($name = 'formActions', $options = []) {
        parent::__construct($name, $options);

        $this->setModelCodigo(isset($this->options['model-codigo']) ? $this->options['model-codigo'] : 'codigo');

        $this->add([
            'type' => 'Hidden',
            'name' => 'submitValue',
            'attributes' => [
                'data-ng-init' => "submitValue = 'save'",
                'value' => '{{ submitValue }}'
            ]
        ]);

        $this->add([
            'type' => 'Button',
            'name' => 'btnSalvar',
            'attributes' => [
                'class' => 'btn btn-primary',
                'data-ng-click' => "submitValue = 'save_and_close'",
                'title' => 'Salvar e Concluir',
                'type' => 'submit'
            ],
            'options' => [
                'label' => "Salvar e Concluir",
                'glyphicon' => 'new-window',
                'dropdown' => [
                    'split' => true,
                    'dropup' => true,
                    'items' => [
                        [
                            'label' => 'Salvar',
                            'item_attributes' => [
                                'data-ng-click' => "submitValue = 'save'",
                                'title' => 'Salvar',
                                'data-ng-triggersubmit' => ''
                            ]
                        ],
                        [
                            'label' => 'Salvar e Incluir',
                            'item_attributes' => [
                                'data-ng-click' => "submitValue = 'save_and_new'",
                                'title' => 'Salvar e Incluir um Novo',
                                'data-ng-triggersubmit' => ''
                            ]
                        ],
                    ]
                ]
            ]
        ]);

        $this->add([
            'type' => 'Button',
            'name' => 'btnCancelar',
            'attributes' => [
                'class' => 'btn btn-default',
                'title' => 'Cancelar',
            ],
            'options' => [
                'label' => "",
                'glyphicon' => 'floppy-remove'
            ],
        ]);

        $this->add([
            'type' => 'Button',
            'name' => 'btnExcluir',
            'attributes' => [
                'class' => 'btn btn-danger',
                'title' => 'Excluir',
                'data-ng-delete' => "optionsDelete",
            ],
            'options' => [
                'label' => "",
                'glyphicon' => 'trash'
            ]
        ]);

        $this->bindModelIdentifier();
    }

    public function bindModelIdentifier() {
        if (isset($this->options['element-codigo'])) {
            $this->get('btnExcluir')->setAttribute('data-ng-disabled', $this->getModelCodigo() . " == ''");
            $this->options['element-codigo']->setAttribute('data-ng-initial', '');
            $this->options['element-codigo']->setAttribute('data-ng-model', $this->getModelCodigo());
        }
    }

    /**
     * @return \Zend\Form\Element\Hidden
     */
    public function getSubmitValue() {
        return $this->get('submitValue');
    }

    public function addSaveAction($action = []) {
        $options = $this->get('btnSalvar')->getOptions();
        $options['dropdown']['items'][] = [
            'label' => $action['label'],
            'item_attributes' => [
                'data-ng-click' => sprintf("submitValue = '%s'", $action['value']),
                'title' => $action['title'],
                'data-ng-triggersubmit' => ''
            ]
        ];
        $this->get('btnSalvar')->setOptions($options);
    }

    public function getModelCodigo() {
        return $this->modelCodigo;
    }

    public function setModelCodigo($modelCodigo) {
        $this->modelCodigo = $modelCodigo;
        return $this;
    }

}
