<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\VideoEvent;
use App\Repositories\Transaction\DBTransaction;
use App\Services\Storage\FileStorage;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use App\Repositories\Eloquent\{
    CastMemberRepository,
    CategoryEloquentRepository,
    GenreEloquentRepository,
    VideoEloquentRepository,
};
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface,
};
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};

class CleanArchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindRepository();

        $this->app->singleton(

            FileStorageInterface::class,
            FileStorage::class
        );

        $this->app->singleton(
            VideoEventManagerInterface::class,
            VideoEvent::class
        );

        $this->app->bind(
            TransactionInterface::class,
            DBTransaction::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function bindRepository()
    {
        $this->app->singleton(
            CategoryRepositoryInterface::class,
            CategoryEloquentRepository::class
        );

        $this->app->singleton(
            GenreRepositoryInterface::class,
            GenreEloquentRepository::class
        );

        $this->app->singleton(
            CastMemberRepositoryInterface::class,
            CastMemberRepository::class
        );


        $this->app->singleton(
            VideoRepositoryInterface::class,
            VideoEloquentRepository::class
        );
    }
}
