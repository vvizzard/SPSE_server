<?php
	require 'dbaccess.php';
	
	header("Content-Type: application/json");

	try{
// 		$inputJSON = file_get_contents("php://input");
		    
// 		$input = json_decode($inputJSON, true);

// 		$temp = validate($input["body"]["data"]);

// 		if (move_uploaded_file($inputJSON, "upload/file.zip"))
        $target_file = "upload/" . basename($_FILES["file"]["name"]);
        
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file))
		    echo json_encode("L'opération a été un succès...");
		    else
		        echo json_encode($_FILES["file"]["error"]);
		    
// 		echo json_encode($temp);
		
	} catch(Exception $e) {
		echo json_encode(["error"=>$e->getMessage()]);
	}