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
            dd('test');
            $message = "Your account has either already been activated, or ";
            $message .= "there is a typo in your activation token.";
            flash($message, 'danger');

            return redirect('/');
        }

        $user->activate();
        flash('Thank you for activating your account.', 'success');

        return redirect('/login');
    }
}
