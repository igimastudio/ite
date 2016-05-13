<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class igima_imagetoelement extends CModule
{
	var $MODULE_ID = "igima.imagetoelement";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	function igima_imagetoelement()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("IGIMA_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("IGIMA_MODULE_DESC");
		
		$this->PARTNER_NAME = GetMessage("IGIMA_PARTNER_NAME");
		$this->PARTNER_URI = "http://igima.ru";
	}
        function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/igima.imagetoelement/install/files/igima_image_to_element_main.js", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/igima.imagetoelement/igima_image_to_element_main.js", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/igima.imagetoelement/install/files/GetMenu.css", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/igima.imagetoelement/GetMenu.css", true, true);
                CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/igima.imagetoelement/install/files/igima_imagetoelement_upload.php", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/igima_imagetoelement_upload.php", true);
                CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/igima.imagetoelement/install/files/images/delete-icon.png", $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/igima.imagetoelement/delete-icon.png", true, true);

		return true;
	}
        function UnInstallFiles()
	{
		DeleteDirFilesEx("/bitrix/js/igima.imagetoelement");
		DeleteDirFilesEx("/bitrix/admin/igima_imagetoelement_upload.php");
                DeleteDirFilesEx("/bitrix/images/igima.imagetoelement");

		return true;
	}
	function DoInstall()
	{
		RegisterModule('igima.imagetoelement');
                RegisterModuleDependences('main', 'OnAdminContextMenuShow', 'igima.imagetoelement', 'IgimaGetMenu', 'GetTopMenu');
                $this->InstallFiles();
	}

	function DoUninstall()
	{
		UnRegisterModule('igima.imagetoelement');
                UnRegisterModuleDependences('main', 'OnAdminContextMenuShow', 'igima.imagetoelement', 'IgimaGetMenu', 'GetTopMenu');
                $this->UnInstallFiles();
	}
}