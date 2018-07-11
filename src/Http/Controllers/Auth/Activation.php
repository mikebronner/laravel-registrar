<?php namespace GeneaLabs\LaravelRegistrar\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class Activation extends Controller
{
    public function show($activationToken) : RedirectResponse
    {
        $users = app(config('auth.providers.users.model'));
        $user = $users->where('activation_token', $activationToken)->first();

        if (! $user) {
            $message = "Your account has either already been activated, or ";
            $message .= "there is a typo in your activation token.";
            app('messenger')->send($message, "Activation Failed", "danger");

            return redirect('/');
        }

        $user->activate();
        app('messenger')->send('Thank you for activating your account.', "Success!", "success", true);

        return redirect('/login');
    }
}
