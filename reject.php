<?php
	require 'dbaccess.php';
	
	//header('Content-Type: application/json; charset=utf-8');
	//$temp = reject($_POST["id"]);
	//echo json_encode($temp);
	//echo json_encode($_POST["users"]);
	
	header("Content-Type: application/json");
	
	
		$inputJSON = file_get_contents("php://input");
		$input = json_decode($inputJSON, true);
		$temp = reject($input["district_id"]);
		echo json_encode($temp);
	