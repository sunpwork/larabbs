<?php

namespace App\Observers;

use App\Models\SiteSetting;

class SiteSettingObserver
{
    public function saved(SiteSetting $siteSetting)
    {
        \Cache::forget($siteSetting->cache_key);
    }
}