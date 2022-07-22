<?php
	$__install = dirname(__FILE__) . "/__install.php";
	define("PHP_FILES_ACCESS_MODE", octdec(substr(decoct(fileperms(__FILE__)), -4, 4)));

	if (!file_exists($__install) || (time()>filectime($__install)+86400)) {
		if (!is_writeable(dirname(__FILE__))) {
			header("Content-Type: text/plain; charset=utf-8");
			echo "Корневая директория \"".dirname(__FILE__)."\" недоступна для записи. Подробнее: https://errors.umi-cms.ru/13010/";
		} else {
			$query = base64_decode("aHR0cDovL3d3dy5pbnN0YWxsLnVtaS1jbXMucnUvZmlsZXMvX19pbnN0YWxsLnBocA==");
			$contents = get_file($query);
			file_put_contents($__install, $contents);
			umask(0);
			chmod($__install, PHP_FILES_ACCESS_MODE);
		}
	}

	function get_file($url) {
		if (!is_callable("curl_init")) {
			throw new Exception('Запрещены функции удаленной загрузки файлов. Подробнее: https://errors.umi-cms.ru/13041/');
		}

		$url = preg_replace('|^https?:\/\/|i', '', $url);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://{$url}");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($ch);
		curl_close($ch);
		return $res;
	}

	include($__install);
