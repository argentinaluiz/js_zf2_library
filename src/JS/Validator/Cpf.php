<?php

namespace JS\Validator;

class Cpf extends CgcAbstract
{
    /**
     * Tamanho do Campo
     * @var int
     */
    protected $size = 11;
 
    /**
     * Modificadores de Dígitos
     * @var array
     */
    protected $modifiers = array(
        array(10,9,8,7,6,5,4,3,2),
        array(11,10,9,8,7,6,5,4,3,2)
    );
}