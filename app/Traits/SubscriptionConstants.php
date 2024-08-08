<?php

namespace App\Traits;
trait SubscriptionConstants
{
    public function subcriptions(): array
    {
        $data['MONTHLY'] = 1;
        $data['YEARLY'] = 2;
        return $data;
    }
}
