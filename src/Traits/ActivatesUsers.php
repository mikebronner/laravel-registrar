<?php namespace GeneaLabs\LaravelRegistrar\Traits;

use Illuminate\Http\Request;

trait ActivatesUsers
{
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['activation_token'] = '';

        return $credentials;
    }
}
