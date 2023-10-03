<?php

namespace App\Services;

use App\Models\Activity;
use Carbon\Carbon;

class ActivityService
{
    public function canUserEnter($userId)
    {
        $dailyEntries = Activity::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        return $dailyEntries < 3;
    }

    public function canEnterCafeteria()
    {
        $dailyTotalEntries = Activity::whereDate('created_at', Carbon::today())
            ->count();

        return $dailyTotalEntries < 50;
    }

    public function saveActivity($userId, $orderId)
    {
        Activity::create([
            'user_id' => $userId,
            'order_id' => $orderId,
        ]);
    }
}
