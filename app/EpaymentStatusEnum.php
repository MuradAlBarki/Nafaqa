<?php

namespace App;

enum EpaymentStatusEnum: string
{
    case Success = 'success';
    case Failed  = 'failed';
    case Pending = 'pending';


     public function realColor(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Success => 'green',
            self::Failed => 'red',
        };
    }
}


