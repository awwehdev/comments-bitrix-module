<?php

namespace Bitrix\Components\Comments\Comments\ORM\Tables;

use Bitrix\Components\Comments\Comments\Tables\UserTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Query\Join;

class CommentTable extends DataManager
{
    public static function getTableName()
    {
        return 'comments_comments';
    }
    
    public static function getMap()
    {
        return array(
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
				'title' => Loc::getMessage('COMMENT_ENTITY_ID_FIELD'),
            ]),
            new IntegerField('user_id'),
            new IntegerField('reply_id'),
            new StringField('topic'),
            new StringField('name'),
            new StringField('email'),
            new StringField('homepage'),
            new StringField('text'),
            new DatetimeField('created_at', ['default_value' => date('d.m.Y H:i:s')]),
            'COMMENT_USER' => (new Reference(
				'COMMENT_USER',
				UserTable::class,
				Join::on('this.USER_ID', 'ref.ID')
			)) ->configureJoinType('inner'),
        );
    }

	public static function validateText()
	{
		return [
			new LengthValidator(null, 1000),
		];
	}
}