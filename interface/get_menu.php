<?
IncludeModuleLangFile(__FILE__);
CJSCore::Init(array("igima_image_to_element_main"));
CModule::IncludeModule("iblock");
class IgimaGetMenu
{
    private static $urlPatterns = array(
        'GetTopMenu' => array(
            'url' => array("/bitrix/admin/iblock_element_admin.php","/bitrix/admin/iblock_section_admin.php","/bitrix/admin/iblock_list_admin.php"),
        )
    );
    public static function getUrlPatterns()
    {
        return self::$urlPatterns;
    }
    public function GetTopMenu(&$topMenu)
    {
        /** @global $APPLICATION CMain */
        global $APPLICATION;
        /** @global $USER CUser */
        global $USER;

        if (!CModule::IncludeModule("iblock") || !$USER->IsAdmin())
        {
            return false;
        }

        if (
                in_array(
                        $APPLICATION->GetCurPage(),self::$urlPatterns[__FUNCTION__]['url']
                ) && intval($_REQUEST['IBLOCK_ID']) > 0
        )
        {
            $strParams = "<input type=\"file\" id=\"file-field\" multiple=\"true\" name=\"file\" />";
            $arDialogParams = array(
                'title' => GetMessage("IGIMAIMAGETOELEMENT_GET_MENU_TITLE"),
                'head' => GetMessage("IGIMAIMAGETOELEMENT_GET_MENU_HEAD"),
                'content' => "<div class=\"igima-image-to-element\">
                            <form action=\"\" name=\"igima_custom_action_form\" id=\"file-form\" method=\"post\">".$strParams."</form>
                            <div id=\"drop-box\" data-iblock=\"".$_REQUEST['IBLOCK_ID']."\" data-charset=\"".SITE_CHARSET."\" data-sid=\"".$_REQUEST['find_section_section']."\">
                                <span>".GetMessage('IGIMAIMAGETOELEMENT_GET_MENU_DROP_BOX')."</span>
                            </div>
                            <div id=\"img-container\">
                            <table id=\"img-list\">
                            <tr>
                            <th>".GetMessage('IGIMAIMAGETOELEMENT_GET_MENU_TH_IMAGE')."</th>
                            <th>".GetMessage('IGIMAIMAGETOELEMENT_GET_MENU_TH_NAME')."</th>
                            <th>".GetMessage('IGIMAIMAGETOELEMENT_GET_MENU_TH_STAT')."</th>
                            <th>".GetMessage('IGIMAIMAGETOELEMENT_GET_MENU_TH_DEL')."</th>
                            </tr></table></div></div>",
                'width' => 840,
                'height' => 600,
                'buttons' => array(
                    array(
                        "title" => GetMessage("IGIMAIMAGETOELEMENT_GET_MENU_BUT_NAME"),
                        "name" => "upload-image",
                        "id" => "imagetoelement-create-elements",
                        "action" => "",
                    ),
                    array(
                        "title" => GetMessage("IGIMAIMAGETOELEMENT_GET_MENU_BUT_NAME_CLOSE"),
                        "name" => "close-reload",
                        "id" => "imagetoelement-close-reload",
                        "action" => "[code]function(){location.reload();}[code]",
                    )
                ),
            );
            $stParams = CUtil::PhpToJsObject($arDialogParams);
            $stParams = str_replace('\'[code]','',$stParams);
            $stParams = str_replace('[code]\'','',$stParams);
            $url = 'javascript:(new BX.CDialog('.$stParams.')).Show(IgimaImageToElementGetFiles())';
            $topMenu[] = array("SEPARATOR" => "1");
            $topMenu[] = array(
                "TEXT" => GetMessage("IGIMAIMAGETOELEMENT_GET_MENU_BUT_NAME"),
                "TITLE" => GetMessage("IGIMAIMAGETOELEMENT_GET_MENU_BUT_NAME"),
                "ICON" => "btn_new_imagetoelement",
                "LINK" => $url
            );
        }

        return true;
    }
}