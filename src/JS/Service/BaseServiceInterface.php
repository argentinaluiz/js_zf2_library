<?php

namespace JS\Service;

use Doctrine\ORM\EntityManager;
use Zend\I18n\Translator\TranslatorInterface;

interface BaseServiceInterface {

    public function find($id);

    public function getReference($id);

    public function getRepository();

    public function create($entity);

    public function update($entity);

    public function remove($codigo);

    public function translate($message);

    public function getEntityManager();

    public function setEntityManager(EntityManager $entityManager);

    public function getEntityName();

    public function setEntityName($entityName);

    public function getTranslator();

    public function setTranslator(TranslatorInterface $translator);

    public function setTextDomain($textDomain);

    public function getTextDomain();
}
