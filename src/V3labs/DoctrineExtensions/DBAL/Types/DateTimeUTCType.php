<?php

namespace V3labs\DoctrineExtensions\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

use DateTime;
use DateTimeZone;

class DateTimeUTCType extends DateTimeType
{
    const DATETIME_UTC = 'datetime_utc';

    public function getName()
    {
        return self::DATETIME_UTC;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $value->setTimeZone(new DateTimeZone('UTC'));
        return $value->format($platform->getDateTimeFormatString());
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $val = DateTime::createFromFormat($platform->getDateTimeFormatString(), $value, new DateTimeZone('UTC'));

        if (!$val) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $val;
    }
}