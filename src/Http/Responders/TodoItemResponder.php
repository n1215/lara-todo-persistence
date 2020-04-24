<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Http\Responders;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use N1215\LaraTodo\Common\TodoItemInterface;
use N1215\LaraTodo\Presentations\TodoItemJsonSerializer;

/**
 * レスポンダー
 * @package N1215\LaraTodo\Http\Responders
 */
class TodoItemResponder
{
    /**
     * @var TodoItemJsonSerializer
     */
    private $serializer;

    /**
     * コンストラクタ
     * @param TodoItemJsonSerializer $serializer
     */
    public function __construct(TodoItemJsonSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Todo項目エンティティの情報でレスポンスを返す
     * @param TodoItemInterface $todoItem
     * @param int $status
     * @return JsonResponse
     */
    public function withEntity(TodoItemInterface $todoItem, $status = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse($this->serializer->serialize($todoItem), $status);
    }

    /**
     * Todo項目エンティティのコレクションの情報でレスポンスを返す
     * @param Collection|TodoItemInterface[] $todoItems
     * @param int $status
     * @return JsonResponse
     */
    public function withEntityCollection(Collection $todoItems, $status = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse($this->serializer->serializeCollection($todoItems), $status);
    }

    /**
     * エラーのレスポンスを返す
     * @param \Exception $e
     * @param int $status
     * @return JsonResponse
     */
    public function error(\Exception $e, $status = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return new JsonResponse([
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
        ], $status);
    }
}
