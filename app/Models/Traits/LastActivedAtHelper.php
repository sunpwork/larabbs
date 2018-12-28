<?php

namespace App\Models\Traits;

use Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        $date = Carbon::now()->toDateString();

        $hash = $this->getHashFromDateString($date);

        $field = $this->getHashField();

        $now = Carbon::now()->toDateTimeString();

        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        $yesterday_date = Carbon::yesterday()->toDateString();

        $hash = $this->getHashFromDateString($yesterday_date);

        $dates = Redis::hGetAll($hash);

        foreach ($dates as $user_id => $actived_at) {
            $user_id = str_replace($this->field_prefix, '', $user_id);

            if ($user = $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }
        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value)
    {
        $date = Carbon::now()->toDateString();

        $hash = $this->getHashFromDateString($date);

        $field = $this->getHashField();

        $datetime = Redis::hGet($hash, $field) ?: $value;

        if ($datetime) {
            return new Carbon($datetime);
        } else {
            return $this->created_at;
        }
    }

    private function getHashFromDateString($date)
    {
        return $this->hash_prefix . $date;
    }

    private function getHashField()
    {
        return $this->field_prefix . $this->id;
    }
}