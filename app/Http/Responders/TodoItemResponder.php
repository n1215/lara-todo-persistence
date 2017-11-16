<?php
declare(strict_types=1);

namespace N1215\LaraTodo\Http\Responders;

use Illuminate\Contracts\Routing\ResponseFactory;
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
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var TodoItemJsonSerializer
     */
    private $serializer;

    /**
     * コンストラクタ
     * @param ResponseFactory $responseFactory
     * @param TodoItemJsonSerializer $serializer
     */
    public function __construct(ResponseFactory $responseFactory, TodoItemJsonSerializer $serializer)
    {
        $this->responseFactory = $responseFactory;
        $this->serializer = $serializer;
    }

    /**
     * Todo項目エンティティの情報でレスポンスを返す
     * @param TodoItemInterface $todoItem
     * @param int $code
     * @return JsonResponse
     */
    public function withEntity(TodoItemInterface $todoItem, $code = Response::HTTP_OK): JsonResponse
    {
        return $this->responseFactory->json($this->serializer->serialize($todoItem), $code);
    }

    /**
     * Todo項目エンティティのコレクションの情報でレスポンスを返す
     * @param Collection|TodoItemInterface[] $todoItems
     * @param int $code
     * @return JsonResponse
     */
    public function withEntityCollection(Collection $todoItems, $code = Response::HTTP_OK): JsonResponse
    {
        return $this->responseFactory->json($this->serializer->serializeCollection($todoItems), $code);
    }


    /**
     * エラーのレスポンスを返す
     * @param \Exception $e
     * @param int $code
     * @return JsonResponse
     */
    public function error(\Exception $e, $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return $this->responseFactory->json([
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
        ], $code);
    }
}
