<?php
require 'db.inc.php';
function is_min($url) {
	return (preg_match("/dragit\.in/i", $url)) ? true : false;
}

function gen_code() {
	$charset = 'abcdefghijklmnopqrstuvwxyz0123456789';
	return substr(str_shuffle($charset), 0, 6);
}

function code_exists($code) {
	$code = mysql_real_escape_string($code);
	$code_exists = mysql_query("SELECT COUNT('url_id') FROM urls WHERE code='$code' LIMIT 1");
	return (mysql_result($code_exists, 0) == 1) ? true : false;
}

function shorten($url, $code) {
	$url = mysql_real_escape_string($url);
	$code = mysql_real_escape_string($code);
	$browser = $_SERVER['HTTP_USER_AGENT'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$host = gethostbyaddr($ip);
	$referrer = $_SERVER['HTTP_REFERER'];
	$query = mysql_query("INSERT INTO urls VALUES ('','$url','$code','$ip','$host','$browser','$referrer',now()) ") or die(mysql_error());
	return $code;
}

function redirect($code) {
	$code = mysql_real_escape_string($code);
	if (code_exists($code)) {
		$url_query = mysql_query("SELECT url FROM urls WHERE code='$code'");
		$url = mysql_result($url_query, 0, 'url');
		header('Location: '.$url);
	}
}

?>