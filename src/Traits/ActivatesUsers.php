<?php namespace GeneaLabs\LaravelRegistrar\Traits;

use App\User;
use Illuminate\Http\Request;

trait ActivatesUsers
{
    protected function attemptLogin(Request $request)
    {
        $user = (new User)
            ->where($this->username(), $request->get($this->username()))
            ->first();

        if (! $user || ! $user->activated_at) {
            // TODO: set login failure message
            return false;
        }

        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }
}
