<?php namespace GeneaLabs\LaravelRegistrar\Traits;

use Carbon\Carbon;
use GeneaLabs\LaravelRegistrar\Notifications\AccountActivation;

trait Activatable
{
    public static function bootActivatable()
    {
        static::creating(function ($user) {
            $user->setAccountActivationTokenIfEligible();
        });

        static::updating(function ($user) {
            $user->setAccountActivationTokenIfEligible();
        });

        static::created(function ($user) {
            $user->sendAccountActivationNoticeIfEligible();
        });

        static::updated(function ($user) {
            $user->sendAccountActivationNoticeIfEligible();
        });
    }

    public function activate()
    {
        $this->activation_token = '';
        $this->activated_at = (new Carbon)->now();
        $this->save();
    }

    public function setAccountActivationTokenIfEligible()
    {
        if (! $this->isActivated && ! $this->hasActivationToken) {
            $this->activation_token = str_random(64);
            $this->activated_at = null;
            $this->save();
        }
    }

    public function sendAccountActivationNoticeIfEligible()
    {
        if ($this->canBeActivated && ! $this->isActivated) {
            $this->notify(new AccountActivation($this));
        }
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function getCanBeActivatedAttribute() : bool
    {
        return true;
    }

    public function getHasActivationTokenAttribute() : bool
    {
        return ($this->activation_token !== null);
    }

    public function getIsActivatedAttribute() : bool
    {
        return ($this->activated_at !== null);
    }
}
