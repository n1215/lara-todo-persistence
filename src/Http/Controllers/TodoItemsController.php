<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Exceptions\EntityNotFoundException;
use N1215\LaraTodo\Exceptions\PersistenceException;
use N1215\LaraTodo\Http\Requests\AddTodoItemRequest;
use N1215\LaraTodo\Http\Responders\TodoItemResponder;
use N1215\LaraTodo\Service\AddTodoItem;
use N1215\LaraTodo\Service\ListTodoItems;
use N1215\LaraTodo\Service\CompleteTodoItem;
use N1215\LaraTodo\Service\ShowTodoItem;

/**
 * Todo項目を管理するコントローラ
 * @package N1215\LaraTodo\Http\Controllers
 */
class TodoItemsController
{
    /**
     * @var TodoItemResponder
     */
    private $responder;

    /**
     * コンストラクタ
     * @param TodoItemResponder $responder
     */
    public function __construct(TodoItemResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Todo項目の一覧を取得
     * @param ListTodoItems $service
     * @return JsonResponse
     */
    public function list(ListTodoItems $service): JsonResponse
    {
        $todoItems = $service->__invoke();
        return $this->responder->withEntityCollection($todoItems);
    }

    /**
     * Todo項目の詳細を取得
     * @param int $id
     * @param ShowTodoItem $service
     * @return JsonResponse
     */
    public function show(int $id, ShowTodoItem $service): JsonResponse
    {
        try {
            $todoItem = $service->__invoke(TodoItemId::of($id));
        } catch (EntityNotFoundException $e) {
            return $this->responder->error($e, Response::HTTP_NOT_FOUND);
        }

        return $this->responder->withEntity($todoItem);
    }

    /**
     * Todo項目を追加する
     * @param AddTodoItemRequest $request
     * @param AddTodoItem $service
     * @return JsonResponse
     */
    public function add(AddTodoItemRequest $request, AddTodoItem $service): JsonResponse
    {
        $inputs = $request->only(['title']);

        try {
            $todoItem = $service->__invoke($inputs);
        } catch (PersistenceException $e) {
            logs()->error($e);
            return $this->responder->error($e);
        }

        return $this->responder->withEntity($todoItem, Response::HTTP_CREATED);
    }

    /**
     * Todo項目を完了にする
     * @param int $id
     * @param CompleteTodoItem $service
     * @return JsonResponse
     */
    public function complete(int $id, CompleteTodoItem $service): JsonResponse
    {
        try {
            $todoItem = $service->__invoke(TodoItemId::of($id));
        } catch (EntityNotFoundException $e) {
            logs()->error($e);
            return $this->responder->error($e, Response::HTTP_NOT_FOUND);
        } catch (PersistenceException $e) {
            logs()->error($e);
            return $this->responder->error($e);
        }

        return $this->responder->withEntity($todoItem);
    }
}
