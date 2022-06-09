<?php
	require 'dbaccess.php';
	
	/*header('Content-Type: application/json; charset=utf-8');
	//$temp = validate($_POST["reponses"]);
	//echo json_encode($temp);
	//echo json_encode($_POST["reponses"]);
	$_POST["reponses"];
	echo json_encode(["tralalallaal"=>34]);*/
	
	header("Content-Type: application/json");
	//if(isset($_POST['reponses'])){
	//  //$dados = json_decode($_POST['pedido'], true);
	//  echo json_encode(['sucesso'=>true]);
	//} else if(isset($_GET['reponses'])) {
	//	echo json_encode(["Lasa get eh"]);
	//} else {
	//	echo json_encode(["valiny"=>"tsisy"]);
	//}
	
	try{
		$inputJSON = file_get_contents("php://input");
		$input = json_decode($inputJSON, true);
		//$temp = validate($input["body"]["data"]);
		//if($temp == true) {
		//	echo json_encode($input["body"]["data"]);
		//} else {
		//	echo json_encode(["Erreur"=>"Erreur"]);
		//}
		
		
		//$tp = (array) $input["body"]["data"][0];
		//$temp = add("reponse_non_valide", $tp);
		$temp = validate($input["body"]["data"]);
		echo json_encode($temp);
	} catch(Exception $e) {
		echo json_encode(["error"=>$e->getMessage()]);
	}