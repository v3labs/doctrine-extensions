<?php

namespace V3labs\DoctrineExtensions\ORM\SoftDeletable;

use DateTime;

trait SoftDeletable
{
    protected $deletedAt;

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    public function isDeleted()
    {
        return null !== $this->getDeletedAt() && $this->getDeletedAt() <= new DateTime();
    }
}