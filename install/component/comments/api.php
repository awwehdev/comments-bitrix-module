<?php

use Bitrix\Components\Comments\Comments\Controller;
use Bitrix\Components\Comments\Comments\Response;
use Bitrix\Components\Comments\Comments\Request;
use Bitrix\Components\Comments\Comments\Traits\NewInstance;

define('NOT_CHECK_PERMISSIONS', true);
define('NEED_AUTH', false);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/comments/comments/Traits/NewInstance.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/comments/comments/ORM/Tables/CommentTable.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/comments/comments/ORM/Entities/Comment.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/comments/comments/Controller.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/comments/comments/Request.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/comments/comments/Response.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/comments/comments/Services/CommentsService.php");

class Api
{
    use NewInstance;

    protected $request;
    protected $response;

    public function __construct()
    {
        $this->request = Request::new();
        $this->response = Response::new();
    }

    public function controller($controller)
    {
        try {
            if ($this->request->isRequestMethodGet() && method_exists($controller, 'index')) {
                $response = $controller->index($this->request);
            } elseif ($this->request->isRequestMethodPost() && method_exists($controller, 'store')) {
                $response = $controller->store($this->request);
            } elseif (($this->request->isRequestMethodPut() || $this->request->isRequestMethodPatch()) && method_exists($controller, 'update')) {
                $response = $controller->update($this->request);
            } elseif ($this->request->isRequestMethodPost() && method_exists($controller, 'destroy')) {
                $response = $controller->destroy($this->request);
            }
        } catch (Exception $exception) {
            $response = $controller->exception($this->request, $exception);
        }

        $this->response->setData($response);

        return $this;
    }

    public function output()
    {
        header('Content-Type: application/json');

        echo $this->response->json();
        exit;
    }
}

Api::new()
    ->controller(Controller::new())
    ->output();
