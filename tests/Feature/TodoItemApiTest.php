<?php

namespace N1215\LaraTodo\Feature;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use N1215\LaraTodo\Common\TodoItemRepositoryInterface;
use N1215\LaraTodo\TestCase;
use N1215\LaraTodo\Impls;

class TodoItemApiTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        /** @var Connection $conn */
        $conn = $this->app->make(DatabaseManager::class)->connection('sqlite');
        $conn->table('todo_items')->delete();
        $conn->unprepared("UPDATE SQLITE_SEQUENCE SET seq = 0 WHERE name = 'todo_items'");
    }

    /**
     * @param string $repositoryClass
     * @dataProvider dataProvider_repositoryClasses
     */
    public function test_crud(string $repositoryClass)
    {
        $this->app->forgetInstance(TodoItemRepositoryInterface::class);
        $this->app->singleton(TodoItemRepositoryInterface::class, $repositoryClass);
        
        $response = $this->get('/todo');
        $response->assertStatus(200);
        $response->assertJson([]);

        $response = $this->get('/todo/1');
        $response->assertStatus(404);

        $title = 'dummy_title1';
        $response = $this->postJson('/todo', ['title' => $title]);
        $response->assertStatus(201);
        $response->assertJson(['id' => 1, 'title' => $title, 'is_completed' => false]);

        $response = $this->put('/todo/1');
        $response->assertStatus(200);
        $response->assertJson(['id' => 1, 'title' => $title, 'is_completed' => true]);

        $response = $this->put('/todo/2');
        $response->assertStatus(404);

        $response = $this->get('/todo');
        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 1, 'title' => $title, 'is_completed' => true]
        ]);

        $title2 = 'dummy_title2';
        $response = $this->postJson('/todo', ['title' => $title2]);
        $response->assertStatus(201);
        $response->assertJson(['id' => 2, 'title' => $title2, 'is_completed' => false]);


        $response = $this->get('/todo');
        $response->assertStatus(200);
        $response->assertJson([
            ['id' => 1, 'title' => $title, 'is_completed' => true],
            ['id' => 2, 'title' => $title2, 'is_completed' => false]
        ]);
    }
    
    public function dataProvider_repositoryClasses(): array
    {
        return [
            [Impls\EloquentAsEntity\TodoItemRepository::class],
            [Impls\EntityContainsEloquent\TodoItemRepository::class],
            [Impls\POPOAndEloquent\TodoItemRepository::class],
            [Impls\POPOAndQueryBuilder\TodoItemRepository::class],
            [Impls\POPOAndPDO\TodoItemRepository::class],
            [Impls\POPOAndAtlas\TodoItemRepository::class],
        ];
    }
}
