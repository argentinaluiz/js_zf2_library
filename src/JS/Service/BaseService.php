<?php

namespace JS\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use JS\Exception\BaseException;

class BaseService implements ServiceLocatorAwareInterface {

    protected $entity;
    protected $entityName;

    /**
     * Entity manager
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;
    protected $translator;

    /**
     * Set up base BaseService options
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @return void
     */
    public function __construct($em, $translator = null, $entityName = null) {
        $this->setEntityManager($em);
        $this->setEntityName($entityName);
        $this->setTranslator($translator);
    }

    /**
     * Find object by id in repository
     * @param int @id id of an object
     * @return Doctrine\ORM\Mapping\Entity
     */
    protected function find($id) {
        return $this->getRepository()->find($id);
    }

    /**
     * Find object reference by id in repository
     * @param int @id id of an object
     * @return Doctrine\ORM\Mapping\Entity
     */
    protected function getReference($id) {
        return $this->getEntityManager()->getReference($this->getEntityName(), $id);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository() {
        return $this->getEntityManager()->getRepository($this->entityName);
    }

    public function create($entity) {
        try {
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
            return $entity;
        } catch (\Exception $ex) {
            $this->chooseHandleTransactionException($ex);
        }
    }

    public function update($entity) {
        try {
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
            return $entity;
        } catch (\Exception $ex) {
            $this->chooseHandleTransactionException($ex);
        }
    }

    public function remove($data = array()) {
        try {
            $this->entity = $this->getRepository()->findOneBy($data);
            if ($this->entity) {
                $this->getEntityManager()->remove($this->entity);
                $this->getEntityManager()->flush();
                return $this->entity;
            } else
                throw new BaseException($this->getTranslator()->translate('e_entity_not_found'), BaseException::ERROR_ENTITY_NOT_EXIST);
        } catch (\Exception $ex) {
            $this->chooseHandleTransactionException($ex);
        }
    }

    protected function begin() {
        if ($this->hasTransaction()) {
            $this->entityManager->getConnection()->rollback();
            throw new \Exception("There is no nested transaction support!");
        }
        try {
            if (!$this->entityManager->getConnection()->isConnected())
                $this->entityManager->getConnection()->connect();
            $this->entityManager->getConnection()->beginTransaction();
        } catch (\Exception $e) {
            throw new \Exception("Não foi possível conectar com o banco de dados: " . $e->getMessage());
        }
    }

    protected function commit() {
        if ($this->hasTransaction()) {
            $this->entityManager->commit();
        }
    }

    /**
     * @return boolean
     */
    protected function hasTransaction() {
        return $this->entityManager->getConnection()->isTransactionActive();
    }

    protected function rollback() {
        if ($this->hasTransaction()) {
            $this->entityManager->getConnection()->rollback();
        }
    }

    protected function close() {
        if ($this->entityManager->getConnection()->isConnected())
            $this->entityManager->getConnection()->close();
    }

    public function chooseHandleTransactionException(\Exception $ex) {
        if ($ex->getPrevious() instanceof \PDOException)
            $this->handleTransactionPDOException($ex);
        else
            $this->handleTransactionException($ex);
    }

    public function handleTransactionException(\Exception $ex) {
        $this->rollback();
        $this->close();
        throw $ex;
    }

    public function handleTransactionPDOException(\Exception $ex) {
        $this->rollback();
        $this->close();
        switch ($ex->getPrevious()->errorInfo[1]) {
            case 1451:
                throw new BaseException($this->getTranslator()->translate('e_pdo_1451'), BaseException::PDO_ERROR_DELETE_REGISTRO, $ex);
            default :
                throw $ex;
        }
    }

    /**
     * Get entity manager
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->entityManager;
    }

    /**
     * Set entity manager
     * @param \Doctrine\ORM\EntityManager $em entity manager to set
     */
    public function setEntityManager($em) {
        $this->entityManager = $em;
    }

    /**
     * Get entity manager
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityName() {
        return $this->entityName;
    }

    /**
     * Set entity manager
     * @param \Doctrine\ORM\EntityManager $entityName entity manager to set
     */
    public function setEntityName($entityName) {
        $this->entityName = $entityName;
    }

    /**
     * Get translator
     * @return \Zend\I18n\Translator\Translator
     */
    public function getTranslator() {
        if (!$this->translator)
            $this->translator = $this->getServiceLocator()->get('jstranslator');
        return $this->translator;
    }

    /**
     * Set translator
     * @param \Zend\I18n\Translator\Translator $translator
     */
    public function setTranslator($translator) {
        $this->translator = $translator;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

}
