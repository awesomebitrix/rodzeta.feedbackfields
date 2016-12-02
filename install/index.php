<?php
/*******************************************************************************
 * rodzeta.feedbackfields - Additional fields for feedback form
 * Copyright 2016 Semenov Roman
 * MIT License
 ******************************************************************************/

// NOTE this file must compatible with php 5.3

defined("B_PROLOG_INCLUDED") and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class rodzeta_feedbackfields extends CModule {

	var $MODULE_ID = "rodzeta.feedbackfields"; // NOTE using "var" for bitrix rules

	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $MODULE_GROUP_RIGHTS;
	public $PARTNER_NAME;
	public $PARTNER_URI;

	//public $MODULE_GROUP_RIGHTS = 'N';
	//public $NEED_MAIN_VERSION = '';
	//public $NEED_MODULES = array();

	function __construct() {
		$this->MODULE_ID = "rodzeta.feedbackfields"; // NOTE for showing module in /bitrix/admin/partner_modules.php?lang=ru

		$arModuleVersion = array();
		include __DIR__ . "/version.php";

		if (!empty($arModuleVersion["VERSION"])) {
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->MODULE_NAME = Loc::getMessage("RODZETA_FEEDBACKFIELDS_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("RODZETA_FEEDBACKFIELDS_MODULE_DESCRIPTION");
		$this->MODULE_GROUP_RIGHTS = "N";

		$this->PARTNER_NAME = "Rodzeta";
		$this->PARTNER_URI = "http://rodzeta.ru/";
	}

	function InstallFiles() {
		CopyDirFiles(
			$_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/admin/" . $this->MODULE_ID,
			$_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/" . $this->MODULE_ID,
			true, true
		);

		return true;
	}

	function UninstallFiles() {
		DeleteDirFilesEx("/bitrix/admin/" . $this->MODULE_ID);
		return true;
	}

	function DoInstall() {
		// check module requirements
		global $APPLICATION;
		if (version_compare(PHP_VERSION, "7", "<")) {
			$APPLICATION->ThrowException(Loc::getMessage("RODZETA_REQUIREMENTS_PHP_VERSION"));
			return false;
		}
		if (!defined("BX_UTF")) {
			$APPLICATION->ThrowException(Loc::getMessage("RODZETA_REQUIREMENTS_BITRIX_UTF8"));
			return false;
		}

		ModuleManager::registerModule($this->MODULE_ID);
		RegisterModuleDependences("main", "OnPageStart", $this->MODULE_ID);
		$this->InstallFiles();
	}

	function DoUninstall() {
		$this->UninstallFiles();
		UnRegisterModuleDependences("main", "OnPageStart", $this->MODULE_ID);
		ModuleManager::unregisterModule($this->MODULE_ID);
	}

}
