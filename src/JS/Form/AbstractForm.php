<?php

namespace JS\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class AbstractForm extends Form implements ObjectManagerAwareInterface {

    protected $objectManager;

    public function __construct(ObjectManager $objectManager, $name = null, $options = array()) {
        parent::__construct($name, $options);

        $this->setObjectManager($objectManager);
        $this->setHydrator(new DoctrineObject($objectManager));
    }

    public function getObjectManager() {
        return $this->objectManager;
    }

    public function setObjectManager(ObjectManager $objectManager) {
        $this->objectManager = $objectManager;
    }

}