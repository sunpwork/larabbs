<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable {
        notify as protected laravelNotify;
    }

    use Traits\ActiveUserHelper;

    use Traits\LastActivedAtHelper;

    /**
     * @param $instance
     * 重写notify方法 使得notification_count自动+1
     */
    public function notify($instance)
    {
        if ($this->id != \Auth::id()) {
            $this->increment('notification_count');
            $this->laravelNotify($instance);
        }
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'introduction', 'avatar',
        'weixin_openid', 'weixin_unionid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class, 'user_id', $this->primaryKey);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'user_id', $this->primaryKey);
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function setPasswordAttribute($value)
    {
        if (strlen($value) != 60) {
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }

    public function idToNameMap()
    {
        $users = User::all(['id', 'name']);

    }
}
