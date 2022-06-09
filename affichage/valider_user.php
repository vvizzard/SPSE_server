<?php
	require 'dbaccess.php';
	
	header("Content-Type: application/json");
	
	try{
		$inputJSON = file_get_contents("php://input");
		$input = json_decode($inputJSON, true);
		$ids = $input["ids"];
		$idsIzy = [];
		for ($i = 0; $i < sizeof($ids); $i++) {
		    $idsIzy["id".$i] = $ids[$i];
		}
		$temp = validerUser($idsIzy, $input["value"]);
		echo json_encode($temp);
	} catch(Exception $e) {
		echo json_encode(["error"=>$e->getMessage()]);
	}