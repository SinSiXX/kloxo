<?php

include_once("dns__lib.php");

class dns__bind extends dns__
{
	function __construct()
	{
		parent::__construct();
	}

	static function unInstallMe()
	{
		setRpmRemoved("bind");

		if (file_exists("/etc/init.d/named")) {
			lunlink("/etc/init.d/named");
		}

		setRpmInstalled("bind-utils");
	}

	static function installMe()
	{
		setRpmInstalled("bind");

		setRpmInstalled("bind-utils");
		setRpmRemoved("bind-chroot");

		$initfile = getLinkCustomfile("/opt/configs/bind/etc/init.d", "named.init");

		if (file_exists($initfile)) {
			lxfile_cp($initfile, "/etc/init.d/named");
		}

		lxshell_return("chkconfig", "named", "on");

		createRestartFile("named");

		self::copyConfigMe();
	}

	static function copyConfigMe()
	{
		setCopyDnsConfFiles('bind');
	}

	function createConfFile($action = null)
	{
		parent::createConfFileTrue('bind', $action);
	}

	function syncCreateConf($action = null)
	{
		parent::syncCreateConfTrue('bind', $action);
	}

	function createAllowTransferIps()
	{
		parent::createAllowTransferIpsTrue('bind');
	}

	function dbactionAdd()
	{
		parent::dbactionAddTrue('bind');
	}

	function dbactionUpdate($subaction)
	{
		parent::dbactionUpdateTrue('bind', $subaction);
	}

	function dbactionDelete()
	{
		parent::dbactionDeleteTrue('bind');
		exec("rndc reconfig");
	}

	function dosyncToSystemPost()
	{
		 // MR -- no action here
	}
}