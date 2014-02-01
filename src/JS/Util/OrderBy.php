<?php

namespace JS\Util;

class OrderBy extends \Doctrine\ORM\Query\Expr\OrderBy {

    public function __construct($sort = null, $order = null) {
        parent::__construct($sort, $order);
    }

    public function addMap(array $map, array $order) {
        foreach ($order as $field => $orientation) {
            if (isset($map[$field])) {
                $f = $map[$field];
                if (isset($f["alias"]))
                    $this->add($f["alias"] . '.' . $field, $orientation);
                else
                    $this->add($field, $orientation);
            }
        }
        return $this;
    }

    public function add($sort, $order = null) {
        if ($order != null) {
            if (is_int($order))
                $order = ($order == 0) ? 'ASC' : 'DESC';
            else
                $order = (strtoupper($order) == 'ASC') ? 'ASC' : 'DESC';
        }
        parent::add($sort, $order);
    }

}