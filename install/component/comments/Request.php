<?php

namespace Bitrix\Components\Comments\Comments;

use Bitrix\Components\Comments\Comments\Traits\NewInstance;

class Request
{
    use NewInstance;

    protected $data = [];

    public function __construct()
    {
        $this->getDataFromInput();
    }

    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isRequestMethodPost()
    {
        return $this->getRequestMethod() === 'POST';
    }

    public function isRequestMethodGet()
    {
        return $this->getRequestMethod() === 'GET';
    }

    public function isRequestMethodPut()
    {
        return $this->getRequestMethod() === 'PUT';
    }

    public function isRequestMethodPatch()
    {
        return $this->getRequestMethod() === 'PATCH';
    }

    public function isRequestMethodDelete()
    {
        return $this->getRequestMethod() === 'DELETE';
    }

    public function getDataFromInput()
    {
        $input = file_get_contents('php://input');

        if (!empty($input)) {
            $this->data = json_decode($input, true);
        }

        if (!empty($_REQUEST)) {
            $this->data = array_merge($this->data, $_REQUEST);
        }
    }

    public function only($fields)
    {
        return array_intersect_key($this->data, array_flip($fields));
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function get($field, $default = null)
    {
        if (!$this->has($field)) {
            return $default;
        }

        return $this->data[$field];
    }

    public function has($field)
    {
        return !empty($this->data[$field]);
    }
}
