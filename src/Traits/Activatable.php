<?php namespace GeneaLabs\LaravelRegistrar\Traits;

use Carbon\Carbon;
use GeneaLabs\LaravelRegistrar\Notifications\AccountActivation;

trait Activatable
{
    public static function bootActivatable()
    {
        self::initialize();
        self::sendNotification();
    }

    protected static function initialize()
    {
        static::creating(function ($user) {
            $user->activation_token = str_random(64);
            $user->activated_at = null;
        });
    }

    protected static function sendNotification()
    {
        static::created(function ($user) {
            $user->notify(new AccountActivation($user));
        });
    }
    
    public function activate()
    {
        $this->activation_token = '';
        $this->activated_at = (new Carbon)->now();
        $this->save();
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function getIsActivatedAttribute() : bool
    {
        return ($this->activated_at !== null);
    }
}
