<?php

namespace App\Services;

use App\Models\Wallet;
use App\Services\Exceptions\NotEnoughCoinException;

class WalletService
{
    public function charge(Wallet $wallet, int $amount)
    {
        if ($wallet->coins < $amount) {
            throw new NotEnoughCoinException('This wallet does not contain enough coin, could not charge');
        }

        $wallet->coins -= $amount;
        $wallet->save();
    }

    public function refund(Wallet $wallet, int $amount)
    {
        $wallet->coins += $amount;
        $wallet->save();
    }
}
