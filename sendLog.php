<?php
	require_once('config.inc.php');	
	date_default_timezone_set('Asia/Calcutta');
	$input=$_POST['input'];
	$answer=$_POST['answer'];
	$ipa=$_SERVER['REMOTE_ADDR'];
	insertValues(array('input' => $input,'output' => $answer,'ip' => $ipa),'calclog');
	$data=getData("SELECT MAX(id) from calclog",0)[0];
	$output=array('timeofCalc' => date('h:i A'),'IPAddr' => $ipa,'ID' => $data);
	die(json_encode($output));
?>