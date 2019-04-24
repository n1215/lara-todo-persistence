<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls;

use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\TestCase;

class TodoItemRepositoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        /** @var Connection $conn */
        $conn = $this->app->make(DatabaseManager::class)->connection('sqlite');
        $conn->table('todo_items')->delete();
        $conn->unprepared("UPDATE SQLITE_SEQUENCE SET seq = 0 WHERE name = 'todo_items'");
    }

    /**
     * @testdox エンティティを新規作成できる
     * @param string $repositoryClass
     * @dataProvider dataProvider_repositoryClasses
     */
    public function test_new(string $repositoryClass): void
    {
        /** @var TodoItemRepositoryInterface $repository */
        $repository = $this->app->make($repositoryClass);

        $title = 'dummy_title';
        $todoItem = $repository->new(['title' => $title]);

        $this->assertInstanceOf(TodoItemInterface::class, $todoItem);
        $this->assertEquals($title, $todoItem->getTitle()->getValue());
        $this->assertFalse($todoItem->isCompleted());
    }


    /**
     * @testdox エンティティを新規に永続化できる
     * @param string $repositoryClass
     * @dataProvider dataProvider_repositoryClasses
     */
    public function test_persist_new_entity(string $repositoryClass): void
    {
        /** @var TodoItemRepositoryInterface $repository */
        $repository = $this->app->make($repositoryClass);

        $nowString = '2017-11-22 10:00:00';
        $now = Carbon::parse($nowString);

        Carbon::setTestNow($now);

        $title = 'dummy_title';
        $todoItem = $repository->new(['title' => $title]);
        $todoItem = $repository->persist($todoItem);

        Carbon::setTestNow();

        $this->assertDatabaseHas('todo_items', [
            'id' => 1,
            'title' => $title,
            'completed_at' => null,
            'created_at' => $nowString,
            'updated_at' => $nowString
        ]);

        $this->assertInstanceOf(TodoItemInterface::class, $todoItem);
        $this->assertEquals($title, $todoItem->getTitle()->getValue());
        $this->assertFalse($todoItem->isCompleted());
    }

    /**
     * @testdox エンティティを更新できる
     * @param string $repositoryClass
     * @dataProvider dataProvider_repositoryClasses
     */
    public function test_persist_when_entity_exist(string $repositoryClass): void
    {
        /** @var TodoItemRepositoryInterface $repository */
        $repository = $this->app->make($repositoryClass);

        $createdAtString = '2017-11-22 10:00:00';
        $createdAt = Carbon::parse($createdAtString);

        Carbon::setTestNow($createdAt);

        $title = 'dummy_title';
        $todoItem = $repository->new(['title' => $title]);
        $todoItem = $repository->persist($todoItem);

        $updatedAtString = '2017-11-22 11:00:00';
        $updatedAt = Carbon::parse($updatedAtString);
        Carbon::setTestNow($updatedAt);

        $todoItem->markAsCompleted();

        $repository->persist($todoItem);

        Carbon::setTestNow();

        $this->assertDatabaseHas('todo_items', [
            'id' => 1,
            'title' => $title,
            'completed_at' => $updatedAtString,
            'created_at' => $createdAtString,
            'updated_at' => $updatedAtString
        ]);
    }

    /**
     * @testdox エンティティをIDで探せる
     * @param string $repositoryClass
     * @dataProvider dataProvider_repositoryClasses
     */
    public function test_find_returns_entity(string $repositoryClass): void
    {
        /** @var TodoItemRepositoryInterface $repository */
        $repository = $this->app->make($repositoryClass);

        $title = 'dummy_title';
        $todoItem = $repository->new(['title' => $title]);
        $repository->persist($todoItem);

        $todoItem = $repository->find(TodoItemId::of(1));

        $this->assertInstanceOf(TodoItemInterface::class, $todoItem);
        $this->assertEquals($title, $todoItem->getTitle()->getValue());
        $this->assertFalse($todoItem->isCompleted());
    }

    /**
     * @testdox エンティティが存在しない場合nullを返す
     * @param string $repositoryClass
     * @dataProvider dataProvider_repositoryClasses
     */
    public function test_find_returns_null_when_entity_not_exist(string $repositoryClass): void
    {
        /** @var TodoItemRepositoryInterface $repository */
        $repository = $this->app->make($repositoryClass);

        $title = 'dummy_title';
        $todoItem = $repository->new(['title' => $title]);
        $repository->persist($todoItem);

        $todoItem = $repository->find(TodoItemId::of(1000));

        $this->assertNull($todoItem);
    }

    /**
     * @testdox すべてのエンティティを取得できる
     * @param string $repositoryClass
     * @dataProvider dataProvider_repositoryClasses
     */
    public function test_list_returns_all_entities(string $repositoryClass): void
    {
        /** @var TodoItemRepositoryInterface $repository */
        $repository = $this->app->make($repositoryClass);

        $titles = [
            'dummy_title1',
            'dummy_title2',
        ];

        collect($titles)->each(function (string $title) use ($repository) {
            $todoItem = $repository->new(['title' => $title]);
            $repository->persist($todoItem);
        });

        $todoItems = $repository->list();

        $this->assertInstanceOf(Collection::class, $todoItems);
        $this->assertCount(count($titles), $todoItems);

        foreach($todoItems as $index => $todoItem) {
            $this->assertInstanceOf(TodoItemInterface::class, $todoItem);
            $this->assertEquals($titles[$index], $todoItem->getTitle()->getValue());
        }
    }

    public function dataProvider_repositoryClasses(): array
    {
        return [
            [EloquentAsEntity\TodoItemRepository::class],
            [EntityContainsEloquent\TodoItemRepository::class],
            [POPOAndEloquent\TodoItemRepository::class],
            [POPOAndQueryBuilder\TodoItemRepository::class],
            [POPOAndPDO\TodoItemRepository::class],
            [POPOAndAtlas\TodoItemRepository::class],
        ];
    }
}