<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use N1215\LaraTodo\Common\TodoItemId;
use N1215\LaraTodo\Exceptions\EntityNotFoundException;
use N1215\LaraTodo\Exceptions\PersistenceException;
use N1215\LaraTodo\Http\Requests\AddTodoItemRequest;
use N1215\LaraTodo\Presentations\TodoItemJsonSerializer;
use N1215\LaraTodo\Service\AddTodoItem;
use N1215\LaraTodo\Service\ListTodoItems;
use N1215\LaraTodo\Service\CompleteTodoItem;
use N1215\LaraTodo\Service\ShowTodoItem;

/**
 * Todo項目を管理するコントローラ
 * @package N1215\LaraTodo\Http\Controllers
 */
class TodoItemsController extends Controller
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var TodoItemJsonSerializer
     */
    private $jsonSerializer;

    /**
     * コンストラクタ
     * @param ResponseFactory $responseFactory
     * @param TodoItemJsonSerializer $jsonSerializer
     */
    public function __construct(ResponseFactory $responseFactory, TodoItemJsonSerializer $jsonSerializer)
    {
        $this->responseFactory = $responseFactory;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * Todo項目の一覧を取得
     * @param ListTodoItems $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(ListTodoItems $service)
    {
        $todoItems = $service->__invoke();
        $json = $this->jsonSerializer->serializeCollection($todoItems);

        return $this->responseFactory->json($json, Response::HTTP_OK);
    }

    /**
     * Todo項目の詳細を取得
     * @param int $id
     * @param ShowTodoItem $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id, ShowTodoItem $service)
    {
        try {
            $todoItem = $service->__invoke(TodoItemId::of($id));
        } catch (EntityNotFoundException $e) {
            return $this->responseFactory->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        $json = $this->jsonSerializer->serialize($todoItem);

        return $this->responseFactory->json($json, Response::HTTP_OK);
    }

    /**
     * Todo項目を追加する
     * @param AddTodoItemRequest $request
     * @param AddTodoItem $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(AddTodoItemRequest $request, AddTodoItem $service)
    {
        $inputs = $request->only(['title']);

        try {
            $todoItem = $service->__invoke($inputs);
        } catch (PersistenceException $e) {
            logger()->error($e);
            return $this->responseFactory
                ->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $json = $this->jsonSerializer->serialize($todoItem);

        return $this->responseFactory->json($json, Response::HTTP_CREATED);
    }

    /**
     * Todo項目を完了にする
     * @param int $id
     * @param CompleteTodoItem $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete(int $id, CompleteTodoItem $service)
    {
        try {
            $todoItem = $service->__invoke(TodoItemId::of($id));

        } catch (EntityNotFoundException $e) {
            logger()->error($e);
            return $this->responseFactory->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);

        } catch (PersistenceException $e) {
            logger()->error($e);
            return $this->responseFactory->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $json = $this->jsonSerializer->serialize($todoItem);

        return $this->responseFactory->json($json, Response::HTTP_OK);
    }
}
