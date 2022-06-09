<?php
// 
$servername = "localhost";
$username = "root";
$password = "root";

function getConnection()
{
  global $servername, $username, $password;
  try {
    $conn = new PDO("mysql:host=$servername;dbname=spse2", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  }
  catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
}

function validerUser($ids, $val)
{
  $column = "";
  $value = [];
  $value["value"] = $val;

  foreach ($ids as $key => $value) {
    $column .= "OR id = :" . $key . " ";
    $value[$key] = $value;
  }

  $sql = "UPDATE user SET validate = :value WHERE 1 != 1 " . $column;

  return set($sql, $value);


}

function validate($params)
{
  foreach ($params as $p) {
    try {
      // add("reponse_non_valide", (array)$p);
      $tp = (array)$p;
      $tp1 = set("DELETE FROM reponse_non_valide WHERE id  = " . $tp["id"], []);
      $temp = add("reponse_non_valide", $tp);
    }
    catch (Exception $err) {
    // $tp = (array) $p;
    // update("reponse_non_valide", $tp, $p->id);
    }
  }
  return true;
}

function reject($districtId)
{
  $sql = "UPDATE reponse_non_valide 
            SET comment = 0 
            WHERE user_id in (
                SELECT id FROM user WHERE district_id = " . $districtId . "
            ) ";
  set($sql, []);
  return true;
}

function terminer($districtId)
{
  $sql = "SELECT * FROM reponse_non_valide WHERE user_id in ( SELECT id FROM user WHERE district_id = " . $districtId . ")";
  $nv = get($sql);
  foreach ($nv as $row) {
    $tp1 = set("DELETE FROM reponse WHERE id  = " . $row["id"], []);
    $reponse = [
      "id" => $row["id"],
      "user_id" => $row["user_id"],
      "date" => $row["date"],
      "question_id" => $row["question_id"],
      "line_id" => $row["line_id"],
      "reponse" => $row["reponse"]
    ];
    add("reponse", $reponse);
  }
  set("DELETE FROM reponse_non_valide WHERE user_id in (SELECT id FROM user WHERE district_id = " . $districtId . ")", []);
  return true;
}

function get($sql)
{
  try {
    // var_dump($sql);
    $conn = getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetchAll();
  }
  catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

function set($sql, $params)
{
  try {
    $conn = getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $conn->lastInsertId();
  }
  catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

function getParam($sql, $params)
{
  try {
    $conn = getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetchAll();
  }
  catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

function getParamConn($conn, $sql, $params)
{
  try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetchAll();
  }
  catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}

function addUpdate($table, $params)
{
  $id = false;
  foreach ($params as $key => $value) {
    if ($key == "id")
      $id = $value;
  }
  if ($id == false) {
    return add($table, $params);
  }
  else
    return update($table, $params, $id);
}

function add($table, $params)
{
  $sql = "INSERT INTO " . $table . " (";
  $values = "";
  foreach ($params as $key => $value) {
    $sql .= " " . $key . ",";
    $values .= " :" . $key . ",";
  }
  $sql = substr($sql, 0, -1) . ") VALUES (" . substr($values, 0, -1) . ")";
  // var_dump($sql);
  // var_dump($params);
  return set($sql, $params);
}

function update($table, $params, $id)
{
  $sql = "UPDATE " . $table . " SET ";
  foreach ($params as $key => $value) {
    $sql .= " " . $key . " = :" . $key . " ,";
  }
  $sql = substr($sql, 0, -1) . " WHERE id=:id";
  $params["id"] = $id;
  // var_dump($sql);
  // var_dump($params);
  return set($sql, $params);
}


function getQuestionByQuestionMereId($questionMereId)
{
  $sql = "SELECT * FROM question q WHERE q.question_mere_id = :id OR q.id = :id";
  $params = array("id" => $questionMereId);
  return getParam($sql, $params);
}

function getQuestionByThematique($thematiqueID)
{
  $sql = "
        SELECT * 
        FROM question q 
        WHERE 
            q.question_mere_id in (
                SELECT q.question_mere_id FROM question q WHERE q.indicateur_id in (
                    SELECT id FROM indicateur i WHERE i.thematique_id = :thId )
            ) OR q.id in (SELECT q.question_mere_id FROM question q WHERE q.indicateur_id in (
                SELECT id FROM indicateur i WHERE i.thematique_id = :thId )
        )
    ";
  $params = array("thId" => $thematiqueID);
  return getParam($sql, $params);
}

// k





function getAssocMBocalById($id)
{
  return get("SELECT m.id, ama.nbr, ama.id_miellerie FROM bocal AS m LEFT JOIN assoc_miellerie_bocal AS ama ON ama.id_bocal = m.id WHERE ama.id_miellerie = " . $id . " ORDER BY ama.id_miellerie ASC");
}

function getStock($type)
{
  $sql = "";
  $type == "month" ? $sql = "SELECT MONTHNAME(date_recolte) as mois, fleur.nom as fleur, miellerie.deshumidification, COUNT(production.id) as nbr_lots, SUM(production.qte)as qte FROM `production`
        JOIN fleur ON fleur.id = id_fleur
        JOIN miellerie ON miellerie.id_production = production.id
        WHERE 1 GROUP BY fleur.nom, MONTHNAME(date_recolte), deshumidification" : $sql = "SELECT YEAR(date_recolte) as mois, fleur.nom as fleur, miellerie.deshumidification, COUNT(production.id) as nbr_lots, SUM(production.qte)as qte FROM `production`
        JOIN fleur ON fleur.id = id_fleur
        JOIN miellerie ON miellerie.id_production = production.id
        WHERE 1 GROUP BY fleur.nom, YEAR(date_recolte), deshumidification";
  return get($sql);
}

function getAvances($dateDebut = "", $dateFin = "")
{
  $condition = " ";
  if ($dateDebut != "")
    $condition .= "AND a.date >= \"" . $dateDebut . "\" ";
  if ($dateFin != "")
    $condition .= "AND a.date <= \"" . $dateFin . "\" ";
  return get("SELECT a.*, DATE_FORMAT(a.date,'%d/%m/%Y') as date_c, MONTHNAME(a.date) as mois, YEAR(a.date) as annee, u.nom 
        FROM avance as a 
            JOIN apiculteur u ON u.id = a.id_apiculteur
        WHERE 1 = 1" . $condition . " ORDER BY DATE(a.date) DESC, a.date DESC");
}

function logs($id, $pw)
{
  $sql = "select * from user where email='" . $id . "' AND pw='" . sha1($pw) . "'";
  // var_dump($sql);
  return get($sql);
}

function logsOld($id, $pw)
{
  $sql = "select * from user where email='" . $id . "' AND pw='" . $pw . "'";
  // var_dump($sql);
  return get($sql);
}

// Services funtions
function makeSqlInsertFromArray($table, $data)
{ //     If there is no value, we don't do anything
  if (sizeof($data) == 0)
    return "";

  $col = "";
  $val = "";
  $values = [];

  foreach ($data[0] as $key => $value) {
    $col .= $key . ", ";
  }
  foreach ($data as $dat) {
    foreach ($dat as $key => $value) {
      (is_numeric($value) && ($value[0] != "0" || strlen($value) == 1)) ? $val .= $value : $val .= "'" . str_replace("'", "''", $value) . "'";
      $val = $val . ", ";
    }
    $val = substr($val, 0, -2);
    $values[] = $val;
    $val = "";
  }

  $col = substr($col, 0, -2);


  $sqlUser = "INSERT INTO " . $table . " ( " . $col . " ) VALUES";
  foreach ($values as $val) {
    $sqlUser .= " ( " . $val . " ), ";
  }
  return substr($sqlUser, 0, -2);
}

function getAllQuestionByThematique($thematiqueId)
{
  $sql = "SELECT * FROM (SELECT distinct x.*, i.label as indicateur 
    FROM question x 
      LEFT JOIN  indicateur i ON i.id_question=x.id
    WHERE x.question_mere_id in (
        SELECT r.id FROM question r
        WHERE r.id in (
          SELECT r.question_mere_id
          FROM thematique t
            LEFT JOIN indicateur s ON t.id = s.thematique_id
            LEFT JOIN question r ON s.id_question=r.id
          WHERE t.id = :thId1
        ) OR r.id in (
          SELECT r.id
          FROM thematique t
            LEFT JOIN indicateur s ON t.id = s.thematique_id
            LEFT JOIN question r ON s.id_question=r.id
          WHERE t.id = :thId2 AND r.is_principale = 1
        )
    ) UNION SELECT distinct x.*, i.label as indicateur 
        FROM question x 
          LEFT JOIN  indicateur i ON i.id_question=x.id
        WHERE ( 
          x.id in (
            SELECT v.id FROM question v
            WHERE v.id in (
              SELECT v.question_mere_id
              FROM thematique t
                  LEFT JOIN indicateur b ON t.id = b.thematique_id
                  LEFT JOIN question v ON b.id_question=v.id
              WHERE t.id = :thId3
            ) OR v.id in (
              SELECT v.id
              FROM thematique t
                LEFT JOIN indicateur b ON t.id = b.thematique_id
                LEFT JOIN question v ON b.id_question=v.id
              WHERE t.id = :thId4 AND v.is_principale = 1
            ) 
          )
    )) as first ORDER BY first.id ASC";
  $params = array("thId1" => $thematiqueId, "thId2" => $thematiqueId, "thId3" => $thematiqueId, "thId4" => $thematiqueId);
  return getParam($sql, $params);
}

function findReponsesByThematique(
  $thid = 1, $start = "2020-01-01", $end = "2023-02-01", $district_id = null, $region_id = null, $comment = null, $table = "reponse"
  )
{

  // Parameters and additionals sql if needed
  $entities = [];

  // Get question by thématique
  // Get response by thématique using question
  // JOIN question with reponse
  // Filter the result
  $sql =
    "
      SELECT 
        qs.label, 
        resp.*, 
        dist.id as district_id, 
        dist.label as district, 
        region.id as region_id, 
        region.label as region,
        STR_TO_DATE(resp.date, \"%d-%m-%Y\") as daty
      FROM (
        SELECT * FROM (SELECT distinct x.*, i.label as indicateur 
    FROM question x 
      LEFT JOIN  indicateur i ON i.id_question=x.id
    WHERE x.question_mere_id in (
        SELECT r.id FROM question r
        WHERE r.id in (
          SELECT r.question_mere_id
          FROM thematique t
            LEFT JOIN indicateur s ON t.id = s.thematique_id
            LEFT JOIN question r ON s.id_question=r.id
          WHERE t.id = :thId1
        ) OR r.id in (
          SELECT r.id
          FROM thematique t
            LEFT JOIN indicateur s ON t.id = s.thematique_id
            LEFT JOIN question r ON s.id_question=r.id
          WHERE t.id = :thId2 AND r.is_principale = 1
        )
    ) UNION SELECT distinct x.*, i.label as indicateur 
        FROM question x 
          LEFT JOIN  indicateur i ON i.id_question=x.id
        WHERE ( 
          x.id in (
            SELECT v.id FROM question v
            WHERE v.id in (
              SELECT v.question_mere_id
              FROM thematique t
                  LEFT JOIN indicateur b ON t.id = b.thematique_id
                  LEFT JOIN question v ON b.id_question=v.id
              WHERE t.id = :thId3
            ) OR v.id in (
              SELECT v.id
              FROM thematique t
                LEFT JOIN indicateur b ON t.id = b.thematique_id
                LEFT JOIN question v ON b.id_question=v.id
              WHERE t.id = :thId4 AND v.is_principale = 1
            ) 
          )
    )) as first ORDER BY first.id ASC
      ) AS qs LEFT JOIN (
        SELECT * FROM " .
    $table .
    " rnv 
        WHERE rnv.question_id in (
            SELECT first.id FROM (SELECT distinct x.*, i.label as indicateur 
            FROM question x 
              LEFT JOIN  indicateur i ON i.id_question=x.id
            WHERE x.question_mere_id in (
                SELECT r.id FROM question r
                WHERE r.id in (
                  SELECT r.question_mere_id
                  FROM thematique t
                    LEFT JOIN indicateur s ON t.id = s.thematique_id
                    LEFT JOIN question r ON s.id_question=r.id
                  WHERE t.id = :thId1
                ) OR r.id in (
                  SELECT r.id
                  FROM thematique t
                    LEFT JOIN indicateur s ON t.id = s.thematique_id
                    LEFT JOIN question r ON s.id_question=r.id
                  WHERE t.id = :thId2 AND r.is_principale = 1
                )
            ) UNION SELECT distinct x.*, i.label as indicateur 
                FROM question x 
                  LEFT JOIN  indicateur i ON i.id_question=x.id
                WHERE ( 
                  x.id in (
                    SELECT v.id FROM question v
                    WHERE v.id in (
                      SELECT v.question_mere_id
                      FROM thematique t
                          LEFT JOIN indicateur b ON t.id = b.thematique_id
                          LEFT JOIN question v ON b.id_question=v.id
                      WHERE t.id = :thId3
                    ) OR v.id in (
                      SELECT v.id
                      FROM thematique t
                        LEFT JOIN indicateur b ON t.id = b.thematique_id
                        LEFT JOIN question v ON b.id_question=v.id
                      WHERE t.id = :thId4 AND v.is_principale = 1
                    ) 
                  )
            )) as first ORDER BY first.id ASC
        )
      ) AS resp ON resp.question_id = qs.id 
        LEFT JOIN user u ON u.id = resp.user_id
        LEFT JOIN district dist ON dist.id = u.district_id
        LEFT JOIN region ON region.id = dist.region_id
      WHERE 1 = 1 
    ";
  $params = array("thId1" => $thid, "thId2" => $thid, "thId3" => $thid, "thId4" => $thid, "thId5" => $thid, "thId6" => $thid, "thId7" => $thid, "thId8" => $thid);

  // More filter if needed
  if ($start != null) {
    $sql .= " AND STR_TO_DATE(resp.date, \"%d-%m-%Y\") >= :start";
    $params["start"] = $start;
  }
  if ($end != null) {
    $sql .= " AND STR_TO_DATE(resp.date, \"%d-%m-%Y\") <= :end";
    $params["end"] = $end;
  }

  // Add filter by date
  // sql .= "AND date like ? ";
  // values.push(date);

  // Add district filter
  // if (districtId != null) {
  //   sql .= "AND district_id = ?";
  //   values.push(districtId);
  // }

  // Execute
  // $params = array("thId1" => $thid, "thId2" => $thid, "thId3" => $thid, "thId4" => $thid, "thId5" => $thid, "thId6" => $thid, "thId7" => $thid, "thId8" => $thid);
  return getParam($sql, $params);
}

class Lined
{
  private $lineId;

  function __construct($lineId)
  {
    $this->lineId = $lineId;
  }

  function isSame($i)
  {
    return $i["line_id"] == $this->lineId;
  }
}

function getTableByThematique($th, $start = null, $end = null)
{
  $questions = getQuestionByThematique($th);
  $resp = findReponsesByThematique($th, $start, $end);

  $lids = [];

  foreach ($resp as $rp) {
    if (!in_array($rp["line_id"], $lids)) {
      $lids[] = $rp["line_id"];
    }
  }

  $lignes = [];

  foreach ($lids as $ls) {
    $lignes[] = array_filter($resp, array(new Lined($ls), 'isSame'));
  }

  $reponse = [];
  $r = [];

  foreach ($lignes as $ligne) {
    foreach ($questions as $qst) {
      $temp = 1;
      foreach ($ligne as $ll) {
        if ($qst["id"] == $ll["question_id"]) {
          $r[] = $ll["reponse"];
          $temp = 0;
          break;
        }
      }
      if ($temp == 1) {
        $r[] = "";
      }
    }
    $reponse[] = $r;
    $r = [];
  }

  return $reponse;
}

function getIndicateurWithCritere($conn, $type = "SUM", $idQuestionForIndicateur, $idQuestionCritere, $start = null, $end = null)
{
  if ($type != "SUM" && $type != "sum")
    $type = "COUNT";

  $params = ["qind" => $idQuestionForIndicateur, "qcrt" => $idQuestionCritere];

  $filter = "";

  if ($start != null) {
    $filter .= " AND STR_TO_DATE(date, \"%d-%m-%Y\") >= :start";
    $params["start"] = $start;
  }
  if ($end != null) {
    $filter .= " AND STR_TO_DATE(date, \"%d-%m-%Y\") <= :end";
    $params["end"] = $end;
  }

  $sql =
    "SELECT 
      SUM(total) as total, critere 
    FROM (
      SELECT 
        a.line_id, a.total, b.critere 
      FROM (
        SELECT 
          line_id, 
          " . $type . "(reponse) AS total 
        FROM reponse 
        WHERE 
          question_id = :qind " . $filter . " 
        GROUP BY line_id
      ) AS a 
      LEFT JOIN (
        SELECT 
          line_id, 
          reponse AS critere 
        FROM reponse 
        WHERE 
          question_id = :qcrt  " . $filter . " 
        GROUP BY line_id
      ) AS b 
      ON a.line_id = b.line_id
    ) AS v 
    GROUP BY critere";

  return getParamConn($conn, $sql, $params);
}

function getIndicateurWithoutCritere($conn, $type = "SUM", $idQuestionForIndicateur, $start = null, $end = null)
{
  if ($type != "SUM" && $type != "sum")
    $type = "COUNT";

  $params = ["qind" => $idQuestionForIndicateur];

  $filter = "";

  if ($start != null) {
    $filter .= " AND STR_TO_DATE(date, \"%d-%m-%Y\") >= :start";
    $params["start"] = $start;
  }
  if ($end != null) {
    $filter .= " AND STR_TO_DATE(date, \"%d-%m-%Y\") <= :end";
    $params["end"] = $end;
  }

  $sql = "SELECT " . $type . "(reponse) AS total FROM reponse WHERE question_id = :qind " . $filter;
  return getParamConn($conn, $sql, $params);
}

function findIndicateurByThematique(
  $thid = 1, $start = null, $end = null, $niveau = null, $comment = null)
{

  try {
    $conn = getConnection();
    $indicateurs = getParamConn($conn, "SELECT * FROM indicateur WHERE thematique_id = :thid", ["thid" => $thid]);


    $valiny = [];

    foreach ($indicateurs as $ind) {
      $checkCritere = getParamConn($conn, "SELECT * FROM question WHERE indicateur_id = :indid", ["indid" => $ind["id"]]);
      if (sizeof($checkCritere) > 0) {
        $temp = [];
        if ($ind["sum"] == 1) {
          $temp = getIndicateurWithCritere($conn, "SUM", $ind["id_question"], $checkCritere[0]["id"], $start, $end);
        }
        else {
          $temp = getIndicateurWithCritere($conn, "COUNT", $ind["id_question"], $checkCritere[0]["id"], $start, $end);
        }
        foreach ($temp as $t) {
          $valiny[] = ["label" => $ind["label"] . " ( " . $t["critere"] . " ) ", "value" => $t["total"]];
        }
      }
      else {
        $temp = [];
        if ($ind["sum"] == 1) {
          $temp = getIndicateurWithoutCritere($conn, "SUM", $ind["id_question"], $start, $end);
        }
        else {
          $temp = getIndicateurWithoutCritere($conn, "COUNT", $ind["id_question"], $start, $end);
        }
        if (isset($temp[0]["total"]))
          $valiny[] = ["label" => $ind["label"], "value" => $temp[0]["total"]];
      }
    }

    return $valiny;

  }
  catch (\Throwable $th) {
    echo $th->__toString();
    return false;
  }

  // More filter if needed
  if ($start != null) {
    $sql .= " AND STR_TO_DATE(resp.date, \"%d-%m-%Y\") >= :start";
    $params["start"] = $start;
  }
  if ($end != null) {
    $sql .= " AND STR_TO_DATE(resp.date, \"%d-%m-%Y\") <= :end";
    $params["end"] = $end;
  }

  return getParam($sql, $params);
}