<?php
	require 'dbaccess.php';
	
	header("Content-Type: application/json");
	
	try{
		$inputJSON = file_get_contents("php://input");
		$input = json_decode($inputJSON, true);
		$temp = pta($input["body"]["data"]);
		echo json_encode($temp);
	} catch(Exception $e) {
		echo json_encode(["error"=>$e->getMessage()]);
	}