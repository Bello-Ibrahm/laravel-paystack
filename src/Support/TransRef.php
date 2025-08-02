<?php

namespace Unicodeveloper\Paystack\Support;

class TransRef
{
    public static function generate(): string
    {
        // return 'TXN_' . strtoupper(uniqid(bin2hex(random_bytes(4)), true));
        return 'TXN_' . uniqid() . '_' . bin2hex(random_bytes(4));
    }
}
