<?php

namespace App\Providers;

use App\Core\Data\Services\FetchTasksService;
use App\Core\Data\Services\StoreCommentService;
use App\Core\Data\Services\StoreTaskService;
use App\Core\Domain\Repositories\CommentRepository;
use App\Core\Domain\Repositories\TaskRepository;
use App\Core\Domain\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FetchTasksService::class, function ($app) {
            return new FetchTasksService($app->make(TaskRepository::class));
        });

        $this->app->singleton(StoreTaskService::class, function ($app) {
            return new StoreTaskService(
                userRepository: $app->make(UserRepository::class),
                taskRepository: $app->make(TaskRepository::class)
            );
        });

        $this->app->singleton(StoreCommentService::class, function ($app) {
            return new StoreCommentService(
                userRepository: $app->make(UserRepository::class),
                taskRepository: $app->make(TaskRepository::class),
                commentRepository: $app->make(CommentRepository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureModels();
        $this->configureUrl();
    }

    /**
     * shouldBeStrict -> Avoid invalid properties from being set on models.
     * unguard -> Allow mass assignment on models.
     */
    private function configureModels(): void
    {
        Model::shouldBeStrict();
        Model::unguard();
    }

    /**
     * Force the application to use HTTPS in production.
     */
    private function configureUrl(): void
    {
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
