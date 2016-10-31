<?php namespace GeneaLabs\LaravelRegistrar\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class Activation extends Controller
{
    public function show($activationToken) : RedirectResponse
    {
        $users = app(config('auth.providers.users.model'));
        $user = $users->where('activation_token', $activationToken)->first();

        if (! $user) {
            $message = "Your account has either already been activated, or ";
            $message .= "there is a typo in your activation token.";
            app('flash')->danger($message);

            return redirect('/');
        }

        $user->activate();
        app('flash')->success('Thank you for activating your account.');

        return redirect('/login');
    }
}
