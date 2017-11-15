<?php

namespace N1215\LaraTodo\Providers;

use Illuminate\Support\ServiceProvider;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\Impls;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // レポジトリの実装切り替え可
        $this->app->singleton(TodoItemRepositoryInterface::class, Impls\EloquentAsEntity\TodoItemRepository::class);
//        $this->app->singleton(TodoItemRepositoryInterface::class, Impls\EntityContainsEloquent\TodoItemRepository::class);
//        $this->app->singleton(TodoItemRepositoryInterface::class, Impls\POPOAndEloquent\TodoItemRepository::class);
    }
}
