<?php

namespace JS\Util;

use JS\Util\OrderBy;

class EntityRepository extends \Doctrine\ORM\EntityRepository {

    private $select;
    private $order;

    public function getSelect() {
        return $this->select;
    }

    public function setSelect($select) {
        $this->select = $select;
    }

    /**
     * @return \JS\Util\OrderBy
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