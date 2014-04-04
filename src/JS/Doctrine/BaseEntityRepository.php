<?php

namespace JS\Doctrine;

use JS\Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\EntityRepository;

class BaseEntityRepository extends EntityRepository {

    const ALIAS_ENTITY = 'em';

    private $order;
    private static $point = '.';
    private static $separator = ',';
    protected $orderByDefault;
    protected $orderByMap;
    protected $camposSelectList;

    public function __construct($entityManager, $class) {
        parent::__construct($entityManager, $class);
    }

    public function getSelectList() {
        $select = '';
        foreach ($this->camposSelectList as $key => $value) {
            if (!is_int($key)) {
                $select .= $key . self::$point . $value . self::$separator;
            } else {
                $select .= $value . self::$separator;
            }
        }
        $select = substr_replace($select, '', -1);
        return $select;
    }

    public function getSelectCount() {
        $primaryKey = $this->getClassMetadata()->getSingleIdentifierFieldName();
        $select = 'count(' . self::ALIAS_ENTITY . self::$point . $primaryKey . ')';
        return $select;
    }

    /**
     * @return \JS\Doctrine\ORM\Query\Expr\OrderBy
     */
    public function getOrderBy() {
        if (!$this->order)
            $this->order = new OrderBy();
        return $this->order;
    }

    public function addPagination($queryBuilder, $firstResult = 0, $maxResult = 0) {
        if ($firstResult != 0 && $maxResult != 0)
            $queryBuilder = $queryBuilder->setFirstResult($firstResult);
        if ($maxResult != 0)
            $queryBuilder = $queryBuilder->setMaxResults($maxResult);
        return $queryBuilder;
    }

}
