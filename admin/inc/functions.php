<?php
function get_request_value($name, $default) {
	if (isset($_POST[$name])) {
		return $_POST[$name];
	} else if (isset($_GET[$name])) {
		return $_GET[$name];
	} else {
		return $default;
	}
}

function humanFileSize($size, $unit = "") {
	if ((!$unit && $size >= 1 << 30) || $unit == "GB") {
		return number_format ( $size / (1 << 30), 2 ) . " GB";
	}
	if ((!$unit && $size >= 1 << 20) || $unit == "MB") {
		return number_format ( $size / (1 << 20), 2 ) . " MB";
	}
	if ((!$unit && $size >= 1 << 10) || $unit == "KB") {
		return number_format ( $size / (1 << 10), 2 ) . " KB";
	}
	return number_format($size) . " B";
}

function sanitizeInput($input) {
	$input = strip_tags($input);
    $input = str_replace('"', '', $input);
    return $input;
}