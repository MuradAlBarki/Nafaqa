<?php

namespace App\Enums;

enum PaymentStatusEnum: int
{
    case Entry = 0;
    case PaidNotVerified = 1;
    case ConfirmedByMother = 2;
    case RejectedByMother = 3;
    case ConfirmedBySystem = 4;
    case RejectedBySystem = 5;

    public function label(): string
    {
        return match($this) {
            self::Entry => 'Entry',
            self::PaidNotVerified => 'Paid but not verified',
            self::ConfirmedByMother => 'Confirmed by mother',
            self::RejectedByMother => 'Rejected by mother',
            self::ConfirmedBySystem => 'Confirmed by system',
            self::RejectedBySystem => 'Rejected by system',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Entry => 'secondary',
            self::PaidNotVerified => 'warning',
            self::ConfirmedByMother => 'success',
            self::RejectedByMother => 'danger',
            self::ConfirmedBySystem => 'success',
            self::RejectedBySystem => 'danger',
        };
    }

    public function realColor(): string
    {
        return match($this) {
            self::Entry => 'gray',
            self::PaidNotVerified => 'yellow',
            self::ConfirmedByMother => 'green',
            self::RejectedByMother => 'red',
            self::ConfirmedBySystem => 'green',
            self::RejectedBySystem => 'red',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Entry => 'document',
            self::PaidNotVerified => 'clock',
            self::ConfirmedByMother => 'check-circle',
            self::RejectedByMother => 'x-circle',
            self::ConfirmedBySystem => 'check-badge',
            self::RejectedBySystem => 'x-mark',
        };
    }
}
