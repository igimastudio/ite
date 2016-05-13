<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$headers = apache_request_headers();
if ($headers["charset"] != 'UTF-8')
{
    $_FILES["my-pic"]["name"] = iconv("UTF-8",$headers["charset"],rawurldecode($_FILES["my-pic"]["name"]));
    $headers["elName"] = iconv("UTF-8",$headers["charset"],rawurldecode($headers["elName"]));
}
$_FILES["my-pic"]["name"] = rawurldecode($_FILES["my-pic"]["name"]);
$arrFile = array_merge(
        $_FILES["my-pic"],array("del" => "N","MODULE_ID" => "iblock"));
$el = new CIBlockElement;
$arLoadProductArray = Array(
    "IBLOCK_ID" => $headers["iblockId"],
    "NAME" => rawurldecode($headers["elName"]),
    "DETAIL_PICTURE" => $arrFile
);
if ($headers["sid"] > 0)
    $arLoadProductArray["IBLOCK_SECTION_ID"] = $headers["sid"];
else
    $arLoadProductArray["IBLOCK_SECTION_ID"] = false;
$PRODUCT_ID = $el->Add($arLoadProductArray,false,false,true);
