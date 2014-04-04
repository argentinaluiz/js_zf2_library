<?php

namespace JS\Doctrine\ORM\Query\Expr;

use Doctrine\ORM\Query\Expr\OrderBy as DoctrineOrderBy;

class OrderBy extends DoctrineOrderBy {

    private static $point = '.';
    private static $asc = 'ASC';
    private static $desc = 'DESC';

    /**
     * @return \Doctrine\ORM\Query\Expr\OrderBy
     */
    public function generateOrderBy(array $map, array $order) {
        foreach ($order as $field => $orientation) {
            if (isset($map[$field])) {
                $alias = $map[$field];
                $this->add($alias . self::$point . $field, $orientation);
            } else {
                if (in_array($field, $map)) {
                    $this->add($field, $orientation);
                }
            }
        }
        return $this;
    }

    /**
     * @param string $sort
     * @param mixed $order Description
     * @return \Doctrine\ORM\Query\Expr\OrderBy
     */
    public function add($sort, $order = 0) {
        if (is_int($order)) {
            $order = ($order == 0) ? self::$asc : self::$desc;
        } else {
            $order = (strtoupper($order) == self::$asc) ? self::$asc : self::$desc;
        }
        parent::add($sort, $order);
        return $this;
    }

}
