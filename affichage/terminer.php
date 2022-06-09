<?php
	require 'dbaccess.php';
	
	header('Content-Type: application/json; charset=utf-8');
	$inputJSON = file_get_contents("php://input");
	$input = json_decode($inputJSON, true);
	$temp = terminer($input["district_id"]);
	echo json_encode($temp);
	//echo json_encode($_POST["users"]);