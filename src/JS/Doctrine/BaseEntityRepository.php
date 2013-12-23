<?php

namespace JS\Doctrine;

use JS\Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\EntityRepository;

class BaseEntityRepository extends EntityRepository {

    const aliasEntity = 'em';

    private $select;
    private $order;
    public $orderByDefault;
    public $orderByMap;
    public $camposSelectList;

    public function __construct($em, $class) {
        parent::__construct($em, $class);
    }

    public function getSelectList() {
        $select = "";
        foreach ($this->camposSelectList as $key => $value) {
            if (is_array($value) && array_key_exists('alias', $value))
                $select .= $value["alias"] . "." . $key . ",";
            else
                $select .= $value . ",";
        }
        $this->setSelect(substr_replace($select, "", -1));
        return $this->getSelect();
    }

    public function getSelectCount() {
        $primaryKey = $this->getClassMetadata()->getSingleIdentifierFieldName();
        $this->setSelect("count(" . self::aliasEntity . "." . $primaryKey . ")");
        return $this->getSelect();
    }

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
