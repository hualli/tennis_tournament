<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\TournamentRepositoryInterface;
use App\Interfaces\GameRepositoryInterface;
use App\Repositories\TournamentRepository;
use App\Repositories\GameRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TournamentRepositoryInterface::class, TournamentRepository::class);
        $this->app->bind(GameRepositoryInterface::class, GameRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
