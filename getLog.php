<?php
	require_once('config.inc.php');
	$gtID=$_GET['after'];
	$data=getData("SELECT * FROM calclog WHERE id>".$gtID,1);
	$array=array('Calcs' => array());
	$i=0;
	while($row=$data->fetch()){
		$row[4]=date('h:i A',strtotime($row[4]));
		//echo json_encode($row);
		$array['Calcs'][$i++]=$row;		
	}
	$json=json_encode($array);
	echo $json;
?>