<?php

declare(strict_types=1);

namespace Aeon\Calendar\Doctrine\Gregorian;

use Aeon\Calendar\Gregorian\DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

final class DateTimeTzType extends \Doctrine\DBAL\Types\DateTimeTzType
{
    public const NAME = 'aeon_datetime_tz';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof DateTime) {
            return $value->format($platform->getDateTimeTzFormatString());
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTime']);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateTime) {
            return $value;
        }

        try {
            $val = DateTime::fromString($value);
        } catch (\Exception $e) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeTzFormatString(), $e);
        }

        if (! $val) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeTzFormatString());
        }

        return $val;
    }
}
