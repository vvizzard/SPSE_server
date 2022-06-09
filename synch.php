<?php
require 'dbaccess.php';

$table = $_GET["table"];

$reponse = [];

if ($table == "reponse") {
	$date = $_GET["date"];
	$reponse = get("SELECT * FROM reponse WHERE STR_TO_DATE(date, '%d-%m-%Y') > STR_TO_DATE('" . $date . "', '%d-%m-%Y')");
}

header('Content-Type: application/json; charset=utf-8');

echo json_encode([
	"reponse" => makeSqlInsertFromArray($table, $reponse)
]);