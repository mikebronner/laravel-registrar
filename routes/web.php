<?php

use GeneaLabs\LaravelRegistrar\Http\Controllers\Auth\Activation;

Route::group(['middleware' => ['web', 'guest']], function () {
    Route::get('/registration/activate/{activationToken}', Activation::class . '@show');
});
