<?php
require 'dbaccess.php';
$user = get("SELECT * FROM user");
// 	$category = get("SELECT * FROM category");
// 	$district = get("SELECT * FROM district");
// 	$indicateur = get("SELECT * FROM indicateur");
// 	$province = get("SELECT * FROM province");
// 	$question = get("SELECT * FROM question");
// 	$region = get("SELECT * FROM region");
$reponse = get("SELECT * FROM reponse");
$reponse_non_valide = get("SELECT * FROM reponse_non_valide");
$pta = get("SELECT * FROM pta");
// 	$thematique = get("SELECT * FROM thematique");

header('Content-Type: application/json; charset=utf-8');
// 	$temp = array();
// 	$temp["user"] = $user;
// 	$temp["category"] = $category;
// 	$temp["district"] = $district;
// 	$temp["indicateur"] = $indicateur;
// 	$temp["province"] = $province;
// 	$temp["question"] = $question;
// 	$temp["region"] = $region;
// 	$temp["reponse"] = $reponse;
// 	$temp["reponse_non_valide"] = $reponse_non_valide;
// 	$temp["thematique"] = $thematique;




// 	echo json_encode(makeSqlInsertFromArray("user", $user));



echo json_encode([
	"user" => makeSqlInsertFromArray("user", $user),
	"reponse" => makeSqlInsertFromArray("reponse", $reponse),
	"reponse_non_valide" => makeSqlInsertFromArray("reponse_non_valide", $reponse_non_valide),
	"pta" => makeSqlInsertFromArray("pta", $pta)
]);
//echo json_encode($_POST["users"]);