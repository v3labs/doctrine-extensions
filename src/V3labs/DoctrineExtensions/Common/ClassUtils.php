<?php

namespace V3labs\DoctrineExtensions\Common;

class ClassUtils
{
    /**
     * Checks if a class or one of its parents uses a trait recursively
     *
     * @param $class
     * @param $trait
     * @return bool
     */
    public static function classUsesTrait($class, $trait)
    {
        do {
            foreach (class_uses($class) as $usedTrait) {
                if ($usedTrait === $trait || self::classUsesTrait($usedTrait, $trait)) {
                    return true;
                }
            }

            $class = get_parent_class($class);
        } while (!empty($class));

        return false;
    }
}