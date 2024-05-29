<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
	"NAME" => Loc::getMessage("COMMENTS_COMPONENT"),
	"DESCRIPTION" => Loc::getMessage("COMMENTS_COMPONENT_DESCRIPTION"),
	"COMPLEX" => "N",
    "CACHE_PATH" => "Y",
	"PATH" => [
        "ID" => Loc::getMessage("COMMENTS_COMPONENT_PATH_ID"),
        "NAME" => Loc::getMessage("COMMENTS_COMPONENT_PATH_NAME"),
		"CHILD" => [
			"ID" => Loc::getMessage("COMMENTS_COMPONENT_CHILD_PATH_ID"),
			"NAME" => GetMessage("COMMENTS")
		]
	],
];
?>