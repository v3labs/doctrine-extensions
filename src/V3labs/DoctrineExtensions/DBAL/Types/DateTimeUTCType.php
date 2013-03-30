<?php

namespace V3labs\DoctrineExtensions\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Types\DateTimeType;

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

        return $value->format($platform->getDateTimeFormatString(), new DateTimeZone('UTC'));
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