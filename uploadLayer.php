<?php
	require 'dbaccess.php';
	
	header("Content-Type: application/json");

	try{
		$inputJSON = file_get_contents("php://input");
		    
		$input = json_decode($inputJSON, true);

// 		$temp = validate($input["body"]["data"]);

		if (file_put_contents("layer/".$input["body"]["name"].".geojson", json_encode($input["body"]["data"])))
		    echo json_encode("L'opération a été un succès...");
		    else
		        echo json_encode("Une erreur est survenue lors de l'opération...");
		    
// 		echo json_encode($temp);
		
	} catch(Exception $e) {
		echo json_encode(["error"=>$e->getMessage()]);
	}