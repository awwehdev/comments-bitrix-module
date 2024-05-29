<?php

namespace Bitrix\Components\Comments\Comments;

use Bitrix\Components\Comments\Comments\ORM\Tables\CommentTable;
use Bitrix\Components\Comments\Comments\Traits\NewInstance;
use Bitrix\Components\Comments\Comments\Request;
use Bitrix\Components\Comments\Comments\Services\CommentsService;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Exception;

class Controller
{
    use NewInstance;

    public function index(Request $request)
    {
        $entity = new CommentTable;

        if ($request->has('topic')) {
            $result = $entity
                ->query()
                ->setSelect(['*'])
                ->where('topic', $request->get('topic'))
                ->fetchAll();
        } else {
            $result = $entity
                ->query()
                ->setSelect(['*'])
                ->fetchAll();
        }

        $data = [];

        foreach ($result as $item) {
            $fields = ['id' => $item['ID'], 'children' => []];

            if ($item['created_at'] instanceof DateTime) {
                $fields['created_at'] = $item['created_at']->format('Uv');
            }

            $data[] = array_merge($item, $fields);
        }

        $data = CommentsService::new()->asTree($data);

        return $this->success($request, $data);
    }

    public function store(Request $request)
    {
        if (!$request->has('name')) {
            throw new \Exception(Loc::getMessage('COMMENT_FIELD_NAME_REQUIRED'));
        }

        if (!$request->has('email')) {
            throw new \Exception(Loc::getMessage('COMMENT_FIELD_EMAIL_REQUIRED'));
        }

        if (!$request->has('text')) {
            throw new \Exception(Loc::getMessage('COMMENT_FIELD_TEXT_REQUIRED'));
        }

        if (!filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            throw new \Exception(Loc::getMessage('COMMENT_FIELD_EMAIL_INVALID'));
        }

        $entity = new CommentTable;
        $result = $entity->add($request->only([
            'user_id',
            'reply_id',
            'name',
            'email',
            'homepage',
            'text',
            'topic',
        ]));

        if (!$result->isSuccess()) {
            throw new Exception(Loc::getMessage('COMMENT_CREATE_ERROR'));
        }

        header('Content-Type: application/json');

        return [
            'status' => true,
            'message' => Loc::getMessage('COMMENT_CREATED'),
            'data' => [
                'id' => $result->getId(),
                'created_at' => (new DateTime)->format('Uv'),
            ],
        ];
    }

    public function exception(Request $request, $exception)
    {
        header('Content-Type: application/json');
        http_response_code(400);

        return [
            'status' => false,
            'message' => $exception->getMessage(),
            'data' => [
                'message' => $exception->getMessage(),
            ],
        ];
    }

    public function success($request, $data)
    {
        return [
            'status' => true,
            'data' => $data
        ];
    }
}