<?php

namespace App\Services;

use App\Models\SystemLog;

class SystemLogService
{
    public function log($userId, string $action, string $description = null, string $ip = null)
    {
        return SystemLog::create(['user_id'=>$userId,'action'=>$action,'description'=>$description,'ip'=>$ip]);
    }
}