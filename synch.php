<?php
require 'dbaccess.php';

$table = $_GET["table"];

$reponse = [];

if ($table == "reponse") {
	$date = $_GET["id"];
	// $reponse = get("SELECT * FROM reponse WHERE STR_TO_DATE(date, '%d-%m-%Y') > STR_TO_DATE('" . $date . "', '%d-%m-%Y')");
	$reponse = get("SELECT * FROM reponse WHERE id > " . $date);
}
else if ($table == "reponse_non_valide") {
	$userId = $_GET["user_id"];
	$reponse = getParam(
		"SELECT * FROM reponse_non_valide WHERE user_id in (
			SELECT id FROM user WHERE district_id in (
				SELECT id FROM district WHERE region_id in (
					SELECT region_id FROM district WHERE id in (
						SELECT district_id FROM user WHERE id = :userId
					)
				)
			)
		)", array("userId"=>$userId)
	);
}
else if ($table == "user") {
	$reponse = $user = get("SELECT * FROM user");
}
else if ($table == "pta") {
	$reponse = get("SELECT * FROM pta");
}

header('Content-Type: application/json; charset=utf-8');

echo json_encode([
	"reponse" => makeSqlInsertFromArray($table, $reponse)
]);