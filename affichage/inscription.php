<?php
	require 'dbaccess.php';
	header("Content-Type: application/json");
	try{
		$inputJSON = file_get_contents("php://input");
		$input = json_decode($inputJSON, true);
		$tp = (array) $input["body"]["user"];
		$temp = add("user", $tp);
		echo json_encode($temp==0);
	} catch(Exception $e) {
		echo json_encode(["error"=>$e->getMessage()]);
	}