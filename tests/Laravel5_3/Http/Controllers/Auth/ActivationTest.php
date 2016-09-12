<?php namespace GeneaLabs\LaravelRegistrar\Tests\Laravel5_3\Http\Controllers\Auth;

use GeneaLabs\LaravelRegistrar\Tests\Laravel5_3\TestCase;
use Carbon\Carbon;

class ActivationTest extends TestCase
{
    public function testUserRegistrationAddsActivationToken()
    {
        // Arrange
        $this->visit('register')
            ->type('John Doe', 'name')
            ->type('john.dow@noemail.com', 'email')
            ->type('secret', 'password')
            ->type('secret', 'password_confirmation');

        // Act
        $this->press('Register');

        // Assert
        $this->seeInDatabase('users', [
                'name' => 'John Doe',
                'activated_at' => null,
            ])
            ->dontSeeInDatabase('users', [
                'name' => 'John Doe',
                'activation_token' => '',
            ]);
    }

    public function testUnactivatedUserCannotLogIn()
    {
        // Arrange
        app(config('auth.providers.users.model'))->create([
            'name' => 'John Doe',
            'email' => 'john.doe@noemail.com',
            'password' => bcrypt('secret'),
        ]);
        $this->visit('/login')
            ->type('john.doe@noemail.com', 'email')
            ->type('secret', 'password');

        // Act
        $this->press('Login');

        // Assert
        $this->seePageIs('/login');
    }

    public function testActivatedUserCanLogIn()
    {
        // Arrange
        $user = app(config('auth.providers.users.model'))->create([
            'name' => 'John Doe',
            'email' => 'john.doe@noemail.com',
            'password' => bcrypt('secret'),
        ]);
        $user->activation_token = '';
        $user->activated_at = (new Carbon)->now();
        $user->save();
        $this->visit('/login')
            ->type('john.doe@noemail.com', 'email')
            ->type('secret', 'password');

        // Act
        $result = $this->press('Login');

        // Assert
        $this->see('You are logged in!');
    }

    public function testUserCanActivateAccount()
    {
        // Arrange
        $user = app(config('auth.providers.users.model'))->create([
            'name' => 'John Doe',
            'email' => 'john.doe@noemail.com',
            'password' => bcrypt('secret'),
        ]);

        // Act
        $this->visit("registration/activate/{$user->activation_token}");
        $user = app(config('auth.providers.users.model'))
            ->where('name', 'John Doe')
            ->first();

        // Assert
        $this->seePageIs('/login')
            ->dontSeeInDatabase('users', [
                'name' => 'John Doe',
                'activated_at' => null,
            ])
            ->seeInDatabase('users', [
                'name' => 'John Doe',
                'activation_token' => '',
            ])
            ->assertTrue($user->is_activated);
    }
}
