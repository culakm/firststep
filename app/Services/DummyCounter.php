<?php

namespace App\Services;


// tieto su pouzite pre dependency injedtion

use App\Contracts\CounterContract;

class DummyCounter implements CounterContract
{
    public function increment(string $key, ?array $tags = null): int
    {
        return 567;
    }
}