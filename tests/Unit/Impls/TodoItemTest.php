<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Impls;

use Carbon\Carbon;
use N1215\LaraTodo\Common\CompletedAt;
use N1215\LaraTodo\Common\Title;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\TestCase;

class TodoItemTest extends TestCase
{
    /**
     * @testdox IDを取得できる
     * @param callable $entityFactory
     * @dataProvider dataProvider_entityFactories
     */
    public function test_getId(callable $entityFactory)
    {
        $attributes = [
            'id' => 1,
            'title' => 'dummy_title',
            'completed_at' => Carbon::parse('2017-11-22 10:00:00'),
        ];

        /** @var TodoItemInterface $todoItem */
        $todoItem = $entityFactory($attributes);

        $this->assertEquals(TodoItemId::of($attributes['id']), $todoItem->getId());
    }

    /**
     * @testdox タイトルを取得できる
     * @param callable $entityFactory
     * @dataProvider dataProvider_entityFactories
     */
    public function test_getTitle(callable $entityFactory)
    {
        $attributes = [
            'id' => 1,
            'title' => 'dummy_title',
            'completed_at' => Carbon::parse('2017-11-22 10:00:00'),
        ];

        /** @var TodoItemInterface $todoItem */
        $todoItem = $entityFactory($attributes);

        $this->assertEquals(Title::of($attributes['title']), $todoItem->getTitle());
    }

    /**
     * @testdox 完了済みかどうか判定できる
     * @param callable $entityFactory
     * @dataProvider dataProvider_entityFactories
     */
    public function test_isCompleted(callable $entityFactory)
    {
        $dataSets = [
            true => ['id' => 1, 'title' => 'Complete', 'completed_at' =>  Carbon::parse('2017-11-22 10:00:00')],
            false => ['id' => 2, 'title' => 'Incomplete', 'completed_at' => null],
        ];

        foreach($dataSets as $expected => $attributes) {
            /** @var TodoItemInterface $todoItem */
            $todoItem = $entityFactory($attributes);
            $this->assertEquals($expected, $todoItem->isCompleted());
        }
    }

    /**
     * @testdox 完了済みにできる
     * @param callable $entityFactory
     * @dataProvider dataProvider_entityFactories
     */
    public function test_markAsCompleted(callable $entityFactory)
    {
        $attributes = [
            'id' => 1,
            'title' => 'dummy_title',
            'completed_at' => null,
        ];

        /** @var TodoItemInterface $todoItem */
        $todoItem = $entityFactory($attributes);
        $todoItem->markAsCompleted();
        $this->assertTrue($todoItem->isCompleted());
    }

    public function dataProvider_entityFactories(): array
    {
        return [
            // パターン1
            [function (array $attributes){
                return (new EloquentAsEntity\TodoItem())->forceFill($attributes);
            }],

            // パターン2
            [function (array $attributes) {
                return new EntityContainsEloquent\TodoItem((new EntityContainsEloquent\TodoItemRecord())->forceFill($attributes));
            }],

            // パターン3
            [function (array $attributes) {
                return new POPOAndEloquent\TodoItem(
                    TodoItemId::of($attributes['id']),
                    Title::of($attributes['title']),
                    CompletedAt::of($attributes['completed_at'])
                );
            }],

            // パターン4
            [function (array $attributes) {
                return new POPOAndQueryBuilder\TodoItem(
                    TodoItemId::of($attributes['id']),
                    Title::of($attributes['title']),
                    CompletedAt::of($attributes['completed_at'])
                );
            }],

            // パターン5
            [function (array $attributes) {
                return new POPOAndPDO\TodoItem(
                    TodoItemId::of($attributes['id']),
                    Title::of($attributes['title']),
                    CompletedAt::of($attributes['completed_at'])
                );
            }],

            // パターン6
            [function (array $attributes) {
                return new POPOAndAtlas\TodoItem(
                    TodoItemId::of($attributes['id']),
                    Title::of($attributes['title']),
                    CompletedAt::of($attributes['completed_at'])
                );
            }],
        ];
    }
}
