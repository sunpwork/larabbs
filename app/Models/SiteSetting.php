<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['site_name', 'contact_email', 'seo_description', 'seo_keyword'];

    public $cache_key = 'site_settings';
    protected $cache_expire_in_minutes = 1440;

    public function getCached()
    {
        return \Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function () {
            return $this->find(1);
        });
    }
}
