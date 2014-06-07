<?php

namespace JS\Form;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use JS\Stdlib\Hydrator\JSDoctrineObject;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

abstract class AbstractFieldset extends Fieldset implements ObjectManagerAwareInterface {

    protected $objectManager;

    public function __construct(ObjectManager $objectManager, $name = null, $options = []) {
        parent::__construct($name, $options);

        $this->setObjectManager($objectManager);
        $this->setHydrator(new JSDoctrineObject($objectManager));
    }

    public function getObjectManager() {
        return $this->objectManager;
    }

    public function setObjectManager(ObjectManager $objectManager) {
        $this->objectManager = $objectManager;
    }

}
