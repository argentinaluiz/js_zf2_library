<?php

namespace JS\Form;

use Zend\Form\Form;
use Zend\Form\FormInterface;

interface MultiPageFormInterface {

    public function prepareSubForm(Form $form, $isLastForm = false);

    public function addCancelButton(Form $subForm);

    public function addSubmitButton(Form $subForm);

    public function getData($flag = FormInterface::VALUES_NORMALIZED);
}
