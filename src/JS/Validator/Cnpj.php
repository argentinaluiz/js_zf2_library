<?php

namespace JS\Validator;

/**
 * Description of Cgc
 *
 * @author Luiz Carlos
 */
class Cnpj extends CgcAbstract {

    /**
     * Tamanho do Campo
     * @var int
     */
    protected $size = 14;

    /**
     * Modificadores de Dígitos
     * @var array
     */
    protected $modifiers = array(
        array(5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2),
        array(6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2)
    );

}
