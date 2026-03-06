<?php

namespace App\Services\API;

use App\Models\User;

class ShiftService
{
    public function openShift($userId)
    {
        $user = User::findOrFail($userId);

         $shift = $user->shifts()->where('status', 'open')->first();

        if (!$shift) {

            $shift = $user->shifts()->create([
                'safe_id' => 1,
                'start_cash' => 0,
                'start_time' => now('Africa/Cairo'),
                'status' => 'open',
            ]);
        }

        return $shift;
    }

}
