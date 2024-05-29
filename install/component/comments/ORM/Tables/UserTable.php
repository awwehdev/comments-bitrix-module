<?php

namespace Bitrix\Components\Comments\Comments\Tables;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\Relations\OneToMany;

class UserTable extends DataManager
{
    public static function getTableName()
    {
        return 'b_user';
    }
    
    public static function getMap()
    {
        return array(
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
				'title' => Loc::getMessage('COMMENT_ENTITY_ID_FIELD'),
            ]),
            'USER_COMMENT' => (new OneToMany('USER_COMMENT', CommentTable::class, 'COMMENT_USER'))->configureJoinType('inner'),
        );
    }
}