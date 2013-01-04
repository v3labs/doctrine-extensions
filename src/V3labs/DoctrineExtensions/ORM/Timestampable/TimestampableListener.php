<?php

namespace V3labs\DoctrineExtensions\ORM\Timestampable;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs,
    Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Events;


class TimestampableListener implements EventSubscriber
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (is_null($classMetadata->reflClass)) {
            return;
        }

        if (in_array(__NAMESPACE__ . "\\Timestampable", $classMetadata->reflClass->getTraitNames())) {
            /* Map fields */
            $classMetadata->mapField(['fieldName' => 'createdAt', 'type' => 'datetime', 'nullable' => true]);
            $classMetadata->mapField(['fieldName' => 'updatedAt', 'type' => 'datetime', 'nullable' => true]);

            /* Add lifecycle callbacks */
            $classMetadata->addLifecycleCallback('updateTimestampableFields', Events::prePersist);
            $classMetadata->addLifecycleCallback('updateTimestampableFields', Events::preUpdate);
        }
    }

    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }
}