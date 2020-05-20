<?php

declare(strict_types=1);

namespace Ocelot\Ocelot\Calendar\Gregorian;

/**
 * @psalm-immutable
 * @psalm-external-mutation-free
 */
final class DateTime
{
    private Day $day;

    private Time $time;

    private \DateTimeZone $timeZone;

    public function __construct(Day $day, Time $time, ?\DateTimeZone $timeZone = null)
    {
        $this->day = $day;
        $this->time = $time;
        $this->timeZone = $timeZone ? $timeZone : new \DateTimeZone('UTC');
    }

    /**
     * @psalm-pure
     */
    public static function fromDateTime(\DateTimeImmutable $dateTime) : self
    {
        return new self(Day::fromDateTime($dateTime), Time::fromDateTime($dateTime), $dateTime->getTimezone());
    }

    public static function fromString(string $date) : self
    {
        return self::fromDateTime(new \DateTimeImmutable($date));
    }

    public function __toString() : string
    {
        return $this->toISO8601();
    }

    public function year() : Year
    {
        return $this->month()->year();
    }

    public function month() : Month
    {
        return $this->day()->month();
    }

    public function day(): Day
    {
        return $this->day;
    }

    public function time(): Time
    {
        return $this->time;
    }

    public function toDateTimeImmutable() : \DateTimeImmutable
    {
        return (
            new \DateTimeImmutable(
                $this->day->toDateTimeImmutable()->format('Y-m-d'),
                $this->timeZone()
            ))
            ->setTime(
                $this->time->hour(),
                $this->time->minute(),
                $this->time->second(),
                $this->time->microsecond()
            );
    }

    public function format(string $format) : string
    {
        return $this->toDateTimeImmutable()->format($format);
    }

    public function timeZone(): \DateTimeZone
    {
        return $this->timeZone;
    }

    public function toTimeZone(\DateTimeZone $dateTimeZone) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->setTimezone($dateTimeZone));
    }

    public function toISO8601() : string
    {
        return $this->toDateTimeImmutable()->format(\DateTimeInterface::ISO8601);
    }

    public function isDaylight() : bool
    {
        return (bool) $this->toDateTimeImmutable()->format('I');
    }

    public function isSavingTime() : bool
    {
        return !$this->isDaylight();
    }

    public function secondsSinceUnixEpoch() : int
    {
        return (int) $this->toDateTimeImmutable()->format('U');
    }

    public function timestamp() : int
    {
        return $this->secondsSinceUnixEpoch();
    }

    public function addHour() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('+1 hour'));
    }

    public function subHour() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('-1 hour'));
    }

    public function addHours(int $hours) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('+%d hour', $hours)));
    }

    public function subHours(int $hours) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('-%d hour', $hours)));
    }

    public function addMinute() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('+1 minute'));
    }

    public function subMinute() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('-11 minute'));
    }

    public function addMinutes(int $minutes) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('+%d minute', $minutes)));
    }

    public function subMinutes(int $minutes) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('-%d minute', $minutes)));
    }

    public function addSecond() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('+1 second'));
    }

    public function subSecond() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('-1 second'));
    }

    public function addSeconds(int $seconds) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('+%d second', $seconds)));
    }

    public function subSeconds(int $seconds) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('-%d second', $seconds)));
    }

    public function addDay() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('+1 day'));
    }

    public function subDay() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('-1 day'));
    }

    public function addDays(int $days) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('+%d day', $days)));
    }

    public function subDays(int $days) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('-%d day', $days)));
    }

    public function addMonth() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('+1 month'));
    }

    public function subMonth() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('-1 month'));
    }

    public function addMonths(int $months) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('+%d months', $months)));
    }

    public function subMonths(int $months) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('-%d months', $months)));
    }

    public function addYear() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('+1 year'));
    }

    public function subYear() : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify('-1 year'));
    }

    public function addYears(int $years) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('+%d years', $years)));
    }

    public function subYears(int $years) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()->modify(\sprintf('-%d years', $years)));
    }

    public function add(TimeUnit $timeUnit) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()
            ->modify(\sprintf('%s%d seconds', $timeUnit->isPositive() ? '+' : '-', $timeUnit->inSeconds())));
    }

    public function sub(TimeUnit $timeUnit) : self
    {
        return self::fromDateTime($this->toDateTimeImmutable()
            ->modify(\sprintf('%s%d seconds', $timeUnit->isPositive() ? '-' : '+', $timeUnit->inSeconds())));
    }

    public function isAfter(DateTime $dateTime) : bool
    {
        return $this->toDateTimeImmutable() > $dateTime->toDateTimeImmutable();
    }

    public function isAfterOrEqual(DateTime $dateTime) : bool
    {
        return $this->toDateTimeImmutable() >= $dateTime->toDateTimeImmutable();
    }

    public function isBeforeOrEqual(DateTime $dateTime) : bool
    {
        return $this->toDateTimeImmutable() <= $dateTime->toDateTimeImmutable();
    }

    public function isBefore(DateTime $dateTime) : bool
    {
        return $this->toDateTimeImmutable() < $dateTime->toDateTimeImmutable();
    }

    public function to(DateTime $dateTime) : TimePeriod
    {
        return new TimePeriod($this, $dateTime);
    }

    public function from(DateTime $dateTime) : TimePeriod
    {
        return new TimePeriod($dateTime, $this);
    }

    public function distanceTo(DateTime $dateTime) : TimeUnit
    {
        return $this->to($dateTime)->distanceStartToEnd();
    }

    public function distanceFrom(DateTime $dateTime) : TimeUnit
    {
        return $this->from($dateTime)->distanceEndToStart();
    }
}