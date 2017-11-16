<?php

namespace N1215\LaraTodo\Providers;

use Illuminate\Support\ServiceProvider;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\Impls;

class AppServiceProvider extends ServiceProvider
{
    /**
     * レポジトリの実装クラス一覧
     * @var array
     */
    private $repositoryClasses = [
        1 => Impls\EloquentAsEntity\TodoItemRepository::class,
        2 => Impls\EntityContainsEloquent\TodoItemRepository::class,
        3 => Impls\POPOAndEloquent\TodoItemRepository::class,
        4 => Impls\POPOAndQueryBuilder\TodoItemRepository::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // レポジトリの実装切り替え可 パターン 1〜4
        $this->app->singleton(TodoItemRepositoryInterface::class, $this->repositoryClasses[1]);
    }
}
