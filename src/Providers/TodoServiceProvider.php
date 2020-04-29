<?php

declare(strict_types=1);

namespace N1215\LaraTodo\Providers;

use Atlas\Orm\Atlas;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\Impls;
use PDO;

/**
 * Class TodoServiceProvider
 * @package N1215\LaraTodo\Providers
 */
class TodoServiceProvider extends ServiceProvider
{
    /**
     * リポジトリの実装クラス一覧
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // コンフィグのマージ
        $configFilePath = __DIR__ . '/../../config/laratodo.php';
        $this->mergeConfigFrom($configFilePath, 'laratodo');

        // コンフィグのパブリッシュ
        $this->publishes([$configFilePath => $this->app->configPath('laratodo.php')], 'laratodo-config');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/todo.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // リポジトリの実装切り替え
        $this->app->singleton(
            TodoItemRepositoryInterface::class,
            function (Container $app) {
                return $app->make($this->repositoryClasses[config('laratodo.impl')]);
            }
        );

        // PDO用の設定
        $this->app->singleton(Impls\POPOAndPDO\TodoItemRepository::class);
        $this->app->when(Impls\POPOAndPDO\TodoItemRepository::class)
            ->needs(PDO::class)
            ->give(
                function () {
                    return new PDO('sqlite:' . config('database.connections.sqlite.database'));
                }
            );

        // Atlas用の設定
        $this->app->singleton(
            Atlas::class,
            function () {
                return Atlas::new(
                    'sqlite:' . config('database.connections.sqlite.database')
                );
            }
        );
    }
}
