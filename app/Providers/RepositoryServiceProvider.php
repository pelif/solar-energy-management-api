<?php

namespace App\Providers;

use App\Core\Domain\Client\Repositories\ClientRepositoryInterface;
use App\Core\Infrastructure\Persistence\Eloquent\Client\EloquentClientRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ClientRepositoryInterface::class, EloquentClientRepository::class);
        $this->app->bind(
            \App\Core\Domain\Project\Repositories\ProjectRepositoryInterface::class,
            \App\Core\Infrastructure\Persistence\Eloquent\Project\EloquentProjectRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
