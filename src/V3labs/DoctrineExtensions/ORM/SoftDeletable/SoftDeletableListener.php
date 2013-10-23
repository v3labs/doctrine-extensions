<?php

namespace V3labs\DoctrineExtensions\ORM\SoftDeletable;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use V3labs\DoctrineExtensions\Common\ClassUtil;

class SoftDeletableListener implements EventSubscriber
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $reflectionClass = $classMetadata->getReflectionClass();

        if (null === $reflectionClass) {
            return;
        }

        if (ClassUtil::classUsesTrait($reflectionClass->getName(), __NAMESPACE__ . "\\SoftDeletable")) {

            if (!$classMetadata->hasField('deletedAt')) {
                $classMetadata->mapField([
                    'fieldName' => 'deletedAt',
                    'type'      => 'datetime_utc',
                    'nullable'  => true
                ]);
            }
        }
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $classMetadata = $em->getClassMetadata(get_class($entity));
            $reflectionClass = $classMetadata->getReflectionClass();

            if (ClassUtil::classUsesTrait($reflectionClass->getName(), __NAMESPACE__ . "\\SoftDeletable")) {

                $oldValue = $entity->getDeletedAt();

                $entity->setDeletedAt(new \DateTime());
                $em->persist($entity);

                $uow->propertyChanged($entity, 'deletedAt', $oldValue, $entity->getDeletedAt());
                $uow->scheduleExtraUpdate($entity, [
                    'deletedAt' => [$oldValue, $entity->getDeletedAt()]
                ]);
            }
        }
    }

    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata, Events::onFlush];
    }
}