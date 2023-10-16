<?php

namespace App\Traits;

trait LockableTrait
{
    public function getLockoutTime()
    {
        return $this['lockout_time'];
    }


    public function hasLockedTime(): bool
    {
        return $this->getLockoutTime() > 0;
    }
}
