<?
$lib_path = '/bitrix/modules/comments.comments/lib/';

$classes = array(
  "\Comments\Comments" => "$lib_path/Comments.php",
 );

CModule::AddAutoloadClasses("", $classes);
