<?php

declare(strict_types=1);

namespace App\Enums;

enum ReviewStatus: string
{
    case Pending = 'pending';
    case Redeemed = 'redeemed';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pendiente',
            self::Redeemed => 'Canjeado',
        };
    }

    public static function fromGiftRedeemed(bool $redeemed): self
    {
        return $redeemed ? self::Redeemed : self::Pending;
    }
}