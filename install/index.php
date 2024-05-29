<?php

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;

if (!function_exists("curl_init") || !function_exists("json_decode")) {
    return;
}

Loc::loadMessages(__FILE__);

class comments_comments extends CModule
{
    var $MODULE_ID = "comments.comments";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = "N";

    function __construct()
    {
        $arModuleVersion = [];

        include dirname(__FILE__) . "/version.php";

        if (
            is_array($arModuleVersion) &&
            array_key_exists("VERSION", $arModuleVersion)
        ) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = Loc::getMessage("MODULE_COMMENTS_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage(
            "MODULE_COMMENTS_DESCRIPTION"
        );
        $this->PARTNER_NAME = "comments";
        $this->PARTNER_URI = "https://";
    }

    public function DoInstall()
    {
        $this->installFiles();
        \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
        return true;
    }

    public function DoUninstall()
    {
        $this->uninstallFiles();
        \Bitrix\Main\Config\Option::delete($this->MODULE_ID);
        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
        return true;
    }

    public function installFiles()
    {
        CopyDirFiles(
            $_SERVER["DOCUMENT_ROOT"] .
                "/bitrix/modules/" .
                $this->MODULE_ID .
                "/install/component/",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/comments",
            true,
            true
        );
        $this->migrateUp();
        return true;
    }

    public function uninstallFiles()
    {
        DeleteDirFilesEx("/bitrix/components/comments/comments");
        return true;
    }

    public function migrateUp()
    {
        $connection = Application::getConnection();

        try {
            $connection->createTable(
                "comments_comments",
                [
                    "id" => new IntegerField("id", [
                        "column_name" => "id",
                        "primary" => true,
                    ]),
                    "user_id" => new IntegerField("user_id", [
                        "column_name" => "user_id",
                    ]),
                    "reply_id" => new IntegerField("reply_id", [
                        "column_name" => "reply_id",
                        "nullable" => true,
                    ]),
                    "topic" => new StringField("topic", [
                        "column_name" => "topic",
                    ]),
                    "name" => new StringField("name", [
                        "column_name" => "name",
                        "nullable" => true,
                    ]),
                    "email" => new StringField("email", [
                        "column_name" => "email",
                        "nullable" => true,
                    ]),
                    "homepage" => new StringField("homepage", [
                        "column_name" => "homepage",
                        "nullable" => true,
                    ]),
                    "text" => new StringField("text", [
                      "column_name" => "text",
                  ]),
                    "created_at" => new DatetimeField("created_at", [
                        "column_name" => "created_at",
                    ]),
                    "updated_at" => new DatetimeField("updated_at", [
                        "column_name" => "updated_at",
                    ]),
                ],
                ["id"],
                ["id"]
            );

            $connection->query(
                "CREATE INDEX idx_user_id ON comments_comments(user_id);"
            );
            $connection->query(
                "CREATE INDEX idx_reply_id ON comments_comments(reply_id);"
            );
            $connection->query(
                "CREATE INDEX idx_topic ON comments_comments(topic);"
            );
            $connection->query(
                "CREATE INDEX idx_email ON comments_comments(email);"
            );
        } catch (\Exception $exception) {}
    }
}
