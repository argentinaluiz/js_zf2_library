<?php

namespace JS\Form;

use Zend\Form\Fieldset;

class ListFieldset extends Fieldset {

    private $buttonBarTop;

    public function __construct($name = null, $options = array()) {
        parent::__construct($name, $options);
        $this->buttonBarTop = new Fieldset('buttonBarTop');
        $this->add($this->buttonBarTop);
        $this->buttonBarTop->add([
            'type' => 'Button',
            'name' => 'btnAddRegistro',
            'attributes' => [
                'class' => 'btn btn-primary',
                'title' => 'Cadastrar Novo Veículo'
            ],
            'options' => [
                'label' => "",
                'glyphicon' => 'edit'
            ],
        ]);

        $this->buttonBarTop->add([
            'type' => 'Button',
            'name' => 'btnExcluirRegistro',
            'attributes' => [
                'class' => 'btn btn-danger',
                'title' => 'Excluir Veículo'
            ],
            'options' => [
                'label' => "",
                'glyphicon' => 'trash'
            ],
        ]);

        $this->buttonBarTop->add([
            'type' => 'Button',
            'name' => 'btnConsultar',
            'attributes' => [
                'class' => 'btn btn-primary',
                'title' => 'Consultar',
                'style' => 'display:none'
            ],
            'options' => [
                'label' => "",
                'glyphicon' => 'search'
            ],
        ]);

        $this->buttonBarTop->add([
            'type' => 'Button',
            'name' => 'btnClearFilters',
            'attributes' => [
                'class' => 'btn btn-success',
                'title' => 'Limpar Todos Filtros',
            ],
            'options' => [
                'label' => "",
                'glyphicon' => 'unchecked'
            ],
        ]);
    }

}
