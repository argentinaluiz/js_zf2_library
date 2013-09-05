<?php

namespace JS\Form;

use Zend\Form\Form;

interface MultiPageFormInterface {

    public function prepareSubForm(Form $form, $isLastForm = false);

    public function addCancelButton(Form $subForm);
    
    public function addSubmitButton(Form $subForm);
    
    public function addFinishButton(Form $subForm);

    public function getData($flag = \Zend\Form\FormInterface::VALUES_NORMALIZED);
}
