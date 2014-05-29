<?php

namespace JS\Service;

use Doctrine\ORM\EntityManager;
use JS\Exception\BaseException;
use JS\Service\BaseServiceInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BaseService implements BaseServiceInterface, ServiceLocatorAwareInterface {

    protected $entity;
    protected $entityName;

    /**
     * Entity manager
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    protected $translator;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Set up base BaseService options
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return void
     */
    public function __construct(EntityManager $entityManager, $translator = null, $entityName = null) {
        $this->setEntityManager($entityManager)
                ->setEntityName($entityName)
                ->setTranslator($translator);
    }

    /**
     * Find object by id in repository
     * @param mixed @id id of an object
     * @return Doctrine\ORM\Mapping\Entity
     */
    public function find($id) {
        return $this->getRepository()->find($id);
    }

    /**
     * Find object reference by id in repository
     * @param mixed @id id of an object
     * @return Doctrine\ORM\Mapping\Entity
     */
    public function getReference($id) {
        return $this->getEntityManager()->getReference($this->getEntityName(), $id);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository() {
        return $this->getEntityManager()->getRepository($this->entityName);
    }

    /**
     * Persiste a entidade em questao e guarda
     * na variavel $entity
     */
    private function save($entity) {
        try {
            $this->entity = $entity;
            $this->getEntityManager()->persist($this->entity);
            $this->getEntityManager()->flush();
            return $this->entity;
        } catch (\Exception $ex) {
            $this->chooseHandleException($ex);
        }
    }

    public function create($entity) {
        return $this->save($entity);
    }

    public function update($entity) {
        return $this->save($entity);
    }

    public function remove($codigo) {
        try {
            $this->entity = $this->find($codigo);
            if ($this->entity) {
                $this->getEntityManager()->remove($this->entity);
                $this->getEntityManager()->flush();
                return $this->entity;
            }
            throw new BaseException($this->getTranslator()->translate('e_entity_not_found'), BaseException::ERROR_ENTITY_NOT_EXIST);
        } catch (\Exception $ex) {
            $this->chooseHandleException($ex);
        }
    }

    /**
     * Se a exception for PDOException verifica se o erro e 1451 para
     * lanca uma excecao que nao e possivel excluir o registro
     * senao lanca o exception em questao
     */
    public function chooseHandleException(\Exception $exception) {
        if ($exception->getPrevious() instanceof \PDOException) {
            $this->handlePDOException($exception);
        } else {
            throw $exception;
        }
    }

    public function handlePDOException(\Exception $exception) {
        switch ($exception->getPrevious()->errorInfo[1]) {
            case 1451:
                throw new BaseException($this->getTranslator()->translate('e_pdo_1451'), BaseException::PDO_ERROR_DELETE_REGISTRO, $exception);
            default :
                throw $exception;
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
     * @param \Doctrine\ORM\EntityManager $entityManager entity manager to set
     */
    public function setEntityManager(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        return $this;
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
        return $this;
    }

    /**
     * Get translator
     * @return \Zend\I18n\Translator\TranslatorInterface
     */
    public function getTranslator() {
        return $this->translator;
    }

    /**
     * Set translator
     * @param \Zend\I18n\Translator\TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator) {
        $this->translator = $translator;
        return $this;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

}
