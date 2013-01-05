<?php

namespace V3labs\DoctrineExtensions\ORM\Translatable;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs,
    Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Events;

class TranslatableListener implements EventSubscriber {


    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (is_null($classMetadata->reflClass)) {
            return;
        }

        if (in_array(__NAMESPACE__ . "\\Translatable", $classMetadata->reflClass->getTraitNames())) {

            $classMetadata->mapOneToMany([
                'fieldName'    => 'translations',
                'mappedBy'     => 'translatable',
                'indexBy'      => 'locale',
                'cascade'      => ['persist', 'merge', 'remove'],
                'targetEntity' => $classMetadata->getName() . 'Translation'
            ]);
        }

        if (in_array(__NAMESPACE__ . "\\Translation", $classMetadata->reflClass->getTraitNames())) {

            /** Map locale field */
            $classMetadata->mapField(['fieldName' => 'locale', 'type' => 'string']);

            $classMetadata->mapManyToOne([
                'fieldName'    => 'translatable',
                'inversedBy'   => 'translations',
                'joinColumns'  => [
                    [
                        'name'                 => 'translatable_id',
                        'referencedColumnName' => 'id',
                        'onDelete'             => 'CASCADE'
                    ]
                ],
                'targetEntity' => substr($classMetadata->getName(), 0, -11)
            ]);

        }

    }

    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }
}