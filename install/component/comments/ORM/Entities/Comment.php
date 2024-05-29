<?php

namespace Bitrix\Components\Comments\Comments\Entities;

use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\ORM\Fields\DatetimeField;

class Comment extends Entity
{
    public static function getMap()
    {
        return array(
            new IntegerField('ID', ['primary' => true]),
            new IntegerField('user_id'),
            new IntegerField('reply_id'),
            new StringField('topic'),
            new StringField('name'),
            new StringField('email'),
            new StringField('homepage'),
            new StringField('text'),
            new DatetimeField('created_at', ['default_value' => date('d.m.Y H:i:s')]),
        );
    }
}