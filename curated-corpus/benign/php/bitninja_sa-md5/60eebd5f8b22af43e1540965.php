<?php
	include(dirname(__FILE__).'/../../config/config.inc.php');
	include(dirname(__FILE__).'/../../init.php');
	include(dirname(__FILE__).'/sampledatainstall.php');
	
	$sample_data_ins = new SampleDataInstall();
	
	if (Tools::getValue('action') == 'loadSql')
	{
		$sample_data_ins->_installSample();	
	}
	else if (Tools::getValue('action') == 'clearData')
		$sample_data_ins->_setDefaultSettings();
	else if (Tools::getValue('action') == 'removeMessage')
		$sample_data_ins->removeMessage();
	else if (Tools::getValue('action') == 'removeModule')
		$sample_data_ins->uninstall();
	else
		$sample_data_ins->_filesUpload($path = 'import/');
?>