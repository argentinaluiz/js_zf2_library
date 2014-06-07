<?php

namespace JS\Stdlib\Hydrator;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class JSDoctrineObject extends DoctrineObject {

    protected function hydrateByValue(array $data, $object) {
        $tryObject = $this->tryConvertArrayToObject($data, $object);
        $metadata = $this->metadata;

        if (is_object($tryObject)) {
            $object = $tryObject;
        }

        foreach ($data as $field => $value) {
            $setter = 'set' . ucfirst($field);

            if ($metadata->hasAssociation($field)) {
                $target = $metadata->getAssociationTargetClass($field);

                if ($metadata->isSingleValuedAssociation($field)) {
                    if (!method_exists($object, $setter)) {
                        continue;
                    }

                    $value = $this->toOne($target, $this->hydrateValue($field, $value, $data));

                    if (null === $value && !current($metadata->getReflectionClass()->getMethod($setter)->getParameters())->allowsNull()
                    ) {
                        continue;
                    }
                    $object->$setter($value);
                } elseif ($metadata->isCollectionValuedAssociation($field)) {
                    $this->toMany($object, $field, $target, $value);
                }
            } else {
                if (!method_exists($object, $setter)) {
                    continue;
                }
                $value = $this->hydrateValue($field, $value, $data);
                $value = $this->handleTypeConversions($value, $metadata->getTypeOfField($field));
                $object->$setter($value);
            }
        }

        return $object;
    }

}
