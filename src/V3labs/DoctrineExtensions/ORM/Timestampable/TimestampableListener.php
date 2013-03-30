<?php

namespace V3labs\DoctrineExtensions\ORM\Timestampable;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use V3labs\DoctrineExtensions\Common\ClassUtils;

class TimestampableListener implements EventSubscriber
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (is_null($classMetadata->reflClass)) {
            return;
        }

        if (ClassUtils::classUsesTrait($classMetadata->reflClass->getName(), __NAMESPACE__ . "\\Timestampable")) {

            if (!$classMetadata->hasField('createdAt') && !$classMetadata->hasField('updatedAt')) {
                $classMetadata->mapField([
                    'fieldName' => 'createdAt',
                    'type'      => 'datetime_utc',
                    'nullable'  => true
                ]);

                $classMetadata->mapField([
                    'fieldName' => 'updatedAt',
                    'type'      => 'datetime_utc',
                    'nullable'  => true
                ]);

                $classMetadata->addLifecycleCallback('updateTimestampableFields', Events::prePersist);
                $classMetadata->addLifecycleCallback('updateTimestampableFields', Events::preUpdate);
            }
        }
    }

    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }
}