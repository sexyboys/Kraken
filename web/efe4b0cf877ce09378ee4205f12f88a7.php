<?php

if (false && $_GET['password'] != 'null') {
	die(-1);
}

$code = 0;

if (true) {
	if (!apc_clear_cache()) {
		$code += 1;
	}
}

if (true) {
	if (!apc_clear_cache('user')) {
		$code += 2;
	}
}

die(json_encode(array('code' => $code)));