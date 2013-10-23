<?php

namespace V3labs\DoctrineExtensions\ORM\Translatable;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use V3labs\DoctrineExtensions\Common\ClassMetadataUtil;
use V3labs\DoctrineExtensions\Common\ClassUtil;

class TranslatableListener implements EventSubscriber
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $reflectionClass = $classMetadata->getReflectionClass();

        if (null === $reflectionClass) {
            return;
        }

        if (ClassUtil::classUsesTrait($reflectionClass->getName(), __NAMESPACE__ . '\\Translatable')) {
            if (!$classMetadata->hasAssociation('translations')) {
                $classMetadata->mapOneToMany([
                    'fieldName'     => 'translations',
                    'mappedBy'      => 'translatable',
                    'indexBy'       => 'locale',
                    'cascade'       => ['persist', 'merge', 'remove'],
                    'orphanRemoval' => true,
                    'targetEntity'  => $classMetadata->getName() . 'Translation',
                ]);
            }
        }

        if (ClassUtil::classUsesTrait($reflectionClass->getName(), __NAMESPACE__ . '\\Translation')) {
            if (!$classMetadata->hasAssociation('translatable')) {
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

            if (!$classMetadata->hasField('locale')) {
                $classMetadata->mapField([
                    'fieldName' => 'locale',
                    'type'      => 'string'
                ]);
            }

            $translationConstraint = $classMetadata->getTableName() . '_unique_translation';
            if (!ClassMetadataUtil::hasUniqueConstraint($classMetadata, $translationConstraint)) {
                $classMetadata->setPrimaryTable([
                    'uniqueConstraints' => [[
                        'name'    => $translationConstraint,
                        'columns' => ['translatable_id', 'locale']
                    ]],
                ]);
            }
        }
    }

    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }
}