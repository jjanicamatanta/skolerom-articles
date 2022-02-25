<?php

namespace App\Providers;

use App\Repository\Article\ArticleRepository;
use App\Repository\User\GrepGoalRelationRepository;
use App\Repository\Article\StudentLevelRepository;
use Domain\Article\Repository\ArticleRepositoryInterface;
use Domain\Users\Repository\GrepGoalRelationRepositoryInterface;
use Domain\Article\Repository\StudentLevelRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            GrepGoalRelationRepositoryInterface::class,
            GrepGoalRelationRepository::class,
        );

        $this->app->bind(
            StudentLevelRepositoryInterface::class,
            StudentLevelRepository::class,
        );

        $this->app->bind(
            ArticleRepositoryInterface::class,
            ArticleRepository::class,
        );

        
    }
}
