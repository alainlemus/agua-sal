<?php

declare(strict_types=1);

namespace App\Enums;

enum ContactStatus: string
{
    case Unattended = 'unattended';
    case Attended = 'attended';

    public function label(): string
    {
        return match($this) {
            self::Unattended => 'No atendido',
            self::Attended => 'Atendido',
        };
    }

    public static function fromIsAttended(bool $attended): self
    {
        return $attended ? self::Attended : self::Unattended;
    }
}