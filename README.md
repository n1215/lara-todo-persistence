# LaravelとEloquentの永続化パターンのサンプル

[laravel.osaka #12のスライド](https://speakerdeck.com/n1215/laraveltodomeinmoderutoyong-sok-hua-moderu-at-laravel-dot-osaka-number-12)用のサンプルコード。

簡単なTodo管理Webアプリの要件としてTodoItemInterfaceとTodoItemRepositoryInterfaceを6パターンで実装した例。

Laravelパッケージとして実装

## インストール

```
# 作業ディレクトリを作成
mkdir lara-todo-app
cd lara-todo-app

# このリポジトリをクローン
git clone https://github.com/n1215/lara-todo-persistence

# Laravelアプリケーションを作成
composer create-project --prefer-dist laravel/laravel
cd laravel

# ローカルのComposerパッケージをインストール
composer config repositories.local path "../lara-todo-persistence"
composer require n1215/lara-todo-persistence

# マイグレーション
php artisan migrate
```

# Web API
```
cd lara-todo-app/laravel
php artisan serve
```

- 一覧: GET http://localhost:8000/todo
- 追加: POST http://localhost:8000/todo
- 詳細: GET http://localhost:8000/todo/1
- 完了: PUT http://localhost:8000/todo/1

## 実装したインターフェース
- [\N1215\LaraTodo\Common\TodoItemInterface](src/Common/TodoItemInterface.php)
- [\N1215\LaraTodo\Common\TodoItemRepositoryInterface](src/Common/TodoItemRepositoryInterface.php)

## 実装6パターン
- [1 Eloquent ModelをEntityとする](src/Impls/EloquentAsEntity)
- [2 EntityがEloquent Modelを中に持つ](src/Impls/EntityContainsEloquent)
- [3 POPOのEntityとEloquent Model](src/Impls/POPOAndEloquent)
- [4 POPOのEntityとQuery Builder](src/Impls/POPOAndQueryBuilder)
- [5 POPOのEntityとPDO](src/Impls/POPOAndPDO)
- [6 POPOのEntityとAtlas](src/Impls/POPOAndAtlas) ※ Atlas → [http://atlasphp.io/](http://atlasphp.io/)

## 実装切り替え

### コンフィグをpublish

```
cd lara-todo-app/laravel
php artisan vendor:publish --tag=laratodo-config
```

config/laratodo.phpのimplの数値を書き換える

```php
<?php

return [
    /**
     * 実装クラスの切り替え 1〜6
     * 1 => Impls\EloquentAsEntity\TodoItemRepository::class
     * 2 => Impls\EntityContainsEloquent\TodoItemRepository::class
     * 3 => Impls\POPOAndEloquent\TodoItemRepository::class
     * 4 => Impls\POPOAndQueryBuilder\TodoItemRepository::class
     * 5 => Impls\POPOAndPDO\TodoItemRepository::class
     * 6 => Impls\POPOAndAtlas\TodoItemRepository::class
     */
    'impl' => 1,
];
```
