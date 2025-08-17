<?php

namespace App;

enum EpaymentStatusEnum: int
{
    case Pending = 0;
    case Success = 1;
    case Failed = 2;

   public function label(): string
    {
        return match($this) {
            self::Pending => 'Pending',
            self::Success => 'Success',
            self::Failed => 'Failed',
        };
    }

     public function realColor(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Success => 'green',
            self::Failed => 'red',
        };
    }
}


