<?php
	require 'dbaccess.php';
	$user = get("SELECT * FROM user");
	$category = get("SELECT * FROM category");
	$district = get("SELECT * FROM district");
	$indicateur = get("SELECT * FROM indicateur");
	$province = get("SELECT * FROM province");
	$question = get("SELECT * FROM question");
	$region = get("SELECT * FROM region");
	$reponse = get("SELECT * FROM reponse");
	$reponse_non_valide = get("SELECT * FROM reponse_non_valide");
	$thematique = get("SELECT * FROM thematique");
	
	header('Content-Type: application/json; charset=utf-8');
	$temp = array();
	$temp["user"] = $user;
	$temp["category"] = $category;
	$temp["district"] = $district;
	$temp["indicateur"] = $indicateur;
	$temp["province"] = $province;
	$temp["question"] = $question;
	$temp["region"] = $region;
	$temp["reponse"] = $reponse;
	$temp["reponse_non_valide"] = $reponse_non_valide;
	$temp["thematique"] = $thematique;
	
	
	
	echo json_encode(["user"=>$temp["user"], "reponse"=>$temp["reponse"], "reponse_non_valide"=>$temp["reponse_non_valide"]]);
	//echo json_encode($_POST["users"]);