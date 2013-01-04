<?php

namespace V3labs\DoctrineExtensions\ORM\Timestampable;

use Datetime;

trait Timestampable {

    protected $createdAt;

    protected $updatedAt;

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function updateTimestampableFields()
    {
        if (is_null($this->createdAt)) {
            $this->createdAt = new DateTime('now');
        }

        $this->updatedAt = new DateTime('now');
    }
}