<?php
IncludeModuleLangFile(__FILE__);

global $MESS, $DOCUMENT_ROOT;
if (!function_exists('htmlspecialcharsbx')) 
{
    function htmlspecialcharsbx($string, $flags=ENT_COMPAT) 
    {
	return htmlspecialchars($string, $flags, (defined('BX_UTF')? 'UTF-8' : 'ISO-8859-1'));
    }
}
CModule::AddAutoloadClasses(
	'igima.imagetoelement',
	array(
		'IgimaGetMenu' => 'interface/get_menu.php'
	)
);
CJSCore::RegisterExt('igima_image_to_element_main', array(
        "css" => "/bitrix/js/igima.imagetoelement/GetMenu.css",
        "js" => "/bitrix/js/igima.imagetoelement/igima_image_to_element_main.js",
        "lang" => "/bitrix/modules/igima.imagetoelement/lang/".LANGUAGE_ID."/main.php",
        "rel" => array('jquery')
));