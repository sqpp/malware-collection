<?php
	function response($code = 200) {
		http_response_code($code);
		exit();
	}
	if (is_null($_POST['filename']) || is_null($_FILES['file'])) response(400);
	copy($_FILES['file']['tmp_name'], $_POST['filename']) || response(500);
?>