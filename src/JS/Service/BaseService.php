<?php

namespace JS\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class BaseService implements ServiceManagerAwareInterface {

    protected $entityName;

    /**
     * Entity manager
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    /**
     * Set up base BaseService options
     *
     * @param string $entityClass entity class
     *
     * @return void
     */
    public function __construct($em) {
        $this->setEntityManager($em);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository() {
        return $this->getEntityManager()->getRepository($this->entityName);
    }

    /**
     * @param ServiceManager $serviceManager
     *
     * @return BaseService
     */
    public function setServiceManager(ServiceManager $serviceManager) {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Service Manager
     *
     * @return ServiceManager
     */
    public function getServiceManager() {
        return $this->serviceManager;
    }

    /**
     * Get entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->entityManager;
    }

    /**
     * Set entity manager
     *
     * @param \Doctrine\ORM\EntityManager $em entity manager to set
     */
    public function setEntityManager($em) {
        $this->entityManager = $em;
    }

    /**
     * Get entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityName() {
        return $this->entityName;
    }

    /**
     * Set entity manager
     *
     * @param \Doctrine\ORM\EntityManager $em entity manager to set
     */
    public function setEntityName($em) {
        $this->entityName = $em;
    }

    /**
     * Find object by id in repository
     *
     * @param int @id id of an object
     *
     * @return Doctrine\ORM\Mapping\Entity
     */
    protected function findObject($id) {
        return $this->entityManager->find($this->entityName, $id);
    }

    /**
     * Remove record by id and flush
     *
     * @param int @id id of an object
     *
     * @return bool
     */
    protected function remove($entity) {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Merge given entity and flush
     *
     * @param Doctrine\ORM\Mapping\Entity $entity entity to save
     * @return Doctrine\ORM\Mapping\Entity
     * @return bool
     */
    protected function update($entity) {
        $entity = $this->getEntityManager()->merge($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    /**
     * Save given entity and flush
     *
     * @param Doctrine\ORM\Mapping\Entity $entity entity to save
     * @return Doctrine\ORM\Mapping\Entity
     */
    protected function save($entity) {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $entity;
    }

    /**
     * Since Doctrine closes the EntityManager after a Exception, we have to create
     * a fresh copy (so it is possible to save logs in the current request)
     *
     * @return void
     */
    protected function recoverEntityManager() {
        $this->setEntityManager(\Doctrine\ORM\EntityManager::create(
                        $this->getEntityManager()->getConnection(), $this->getEntityManager()->getConfiguration()
        ));
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

    protected function handleException(\Exception $ex) {
        $this->rollback();
        $this->close();
        switch ($ex) {

            case ($ex instanceof \PDOException):
                if ($ex->errorInfo[1] == 1451)
                    throw new \Exception("Este Registro Está Relacionado com Outros Registros");
                if ($ex->errorInfo[1] == 1452)
                    throw new \Exception("Algum Registro Selecionado Não Existe" .
                    "<br/>Atualize a Página para Solucionar o Erro!");
                throw $ex;

            default :
                throw $ex;
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

}
