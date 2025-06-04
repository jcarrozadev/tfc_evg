<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

/**
 * Class FortifyServiceProvider
 * This service provider is used to configure Laravel Fortify features.
 */
class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * This method is used to set up Fortify's features such as user creation, password reset, and views.
     * @return void
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
    
        Fortify::loginView(fn () => view('login.login'));

        Fortify::requestPasswordResetLinkView(fn () => view('login.passwordRecovery'));
        Fortify::resetPasswordView(function ($request) {
            return view('login.resetPassword', ['request' => $request]);
        });
    }
}
