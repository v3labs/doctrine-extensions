<?php

namespace V3labs\DoctrineExtensions\Common;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;

class ClassMetadataUtils
{
    public static function hasUniqueConstraint(ClassMetadata $classMetadata, $name)
    {
        if (isset($classMetadata->table['uniqueConstraints'])) {
            $items = array_map(function ($item) { return $item['name']; }, $classMetadata->table['uniqueConstraints']);
            return in_array($name, $items);
        }
    }
}