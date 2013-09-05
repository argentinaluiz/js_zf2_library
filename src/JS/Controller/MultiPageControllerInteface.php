<?php

namespace JS\Controller;

use Zend\Form\Form;

interface MultiPageControllerInteface {

    public function getForm();

    public function getContainer();

    public function getSavedForms();

    public function getPotentialForms();

    public function getCurrentSubForm();

    public function getNextSubForm();

    public function getNamespace();
    
    //public function getLastForm();

    public function subFormIsValid(Form $subForm, array $data);

    public function subFormIsLast(Form $subForm);

    public function formIsValid();

    public function destroyContainer();

    public function setNamespace($name);
}
