<?php

namespace N1215\LaraTodo\Providers;

use Atlas\Orm\Atlas;
use Atlas\Orm\AtlasContainer;
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
        5 => Impls\POPOAndPDO\TodoItemRepository::class,
        6 => Impls\POPOAndAtlas\TodoItemRepository::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // レポジトリの実装切り替え可 パターン 1〜4
        $this->app->singleton(TodoItemRepositoryInterface::class, $this->repositoryClasses[5]);

        // PDO用の設定
        $this->app->singleton(Impls\POPOAndPDO\TodoItemRepository::class, function () {
            return new Impls\POPOAndPDO\TodoItemRepository(
                new Impls\POPOAndPDO\TodoItemFactory(),
                new \PDO('sqlite:'. config('database.connections.sqlite.database'))
            );
        });

        // Atlas用の設定
        $this->app->singleton(Atlas::class, function () {
            return Atlas::new(
                'sqlite:'. config('database.connections.sqlite.database')
            );
        });
    }
}
