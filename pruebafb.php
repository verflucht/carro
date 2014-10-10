<?php 

	header('Access-Control-Allow-Origin: *');
	ob_start();
	error_reporting(E_ALL); 
	ini_set('display_errors', 'on');
	header('Content-Type: text/html; charset=UTF-8');  
	session_start();

	if (isset($_SESSION['friendlist'])) {
		var_dump($_SESSION['friendlist']);
	}

	print_r($_SESSION['friendlist']);

 ?>