<?php

namespace Bitrix\Components\Comments\Comments;

use Bitrix\Components\Comments\Comments\Traits\NewInstance;

class Response
{
    use NewInstance;

    protected $response;

    public function __construct($data)
    {
        $this->data($data);
    }

    public function data($data)
    {
        return $this->setData($data);
    }

    public function setData($data)
    {
        if (!is_array($data)) {
            $data = [$data];
        }

        $this->response = $data;

        return $this;
    }

    public function getData()
    {
        return $this->response;
    }

    public function json()
    {
        return json_encode($this->response, JSON_UNESCAPED_UNICODE);
    }
}