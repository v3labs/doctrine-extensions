<?php

namespace V3labs\DoctrineExtensions\ORM\Timestampable;

use DateTime;
use DateTimeZone;

trait Timestampable
{
    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @return \DateTimeZone
     */
    public function getTimestampableTimeZone()
    {
        return new DateTimeZone(date_default_timezone_get() ?: 'UTC');
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        $this->createdAt->setTimezone($this->getTimestampableTimeZone());
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        $this->updatedAt->setTimezone($this->getTimestampableTimeZone());
        return $this->updatedAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function updateTimestampableFields()
    {
        if (null === $this->createdAt) {
            $this->createdAt = new DateTime('now');
        }

        $this->updatedAt = new DateTime('now');
    }
}
