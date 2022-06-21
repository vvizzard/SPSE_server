<?php
// 
$servername = "localhost";
$username = "root";
$password = "root";

function getConnection()
{
    global $servername, $username, $password;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=spse", $username, $password);
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

function pta($params)
{
    try {
        $districtId = (array)$params[0];
        $districtId = $districtId["district_id"];
        foreach ($params as $p) {
            $tp = (array)$p;
            $tp1 = set("DELETE FROM pta WHERE district_id  = " . $districtId . " AND date like \"%" . explode("-", $tp["date"])[2] . "\"", []);
            $temp = add("pta", $tp);
        }
    }
    catch (\Exception $err) {
        throw $err;
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

function terminer($districtId, $thId)
{
    $sql = "SELECT * FROM reponse_non_valide 
    WHERE 
        user_id in ( SELECT id FROM user WHERE district_id = :districtId) 
        AND question_id in (
            SELECT distinct q.id
            FROM question q 
                LEFT JOIN  indicateur i ON i.id_question=q.id
            WHERE q.question_mere_id in (
                SELECT q.id FROM question q
                WHERE q.id in (
                    SELECT q.question_mere_id
                    FROM thematique t
                    LEFT JOIN indicateur i ON t.id = i.thematique_id
                    LEFT JOIN question q ON i.id_question=q.id
                    WHERE t.id = :thid
                ) OR q.id in (
                    SELECT q.id
                    FROM thematique t
                    LEFT JOIN indicateur i ON t.id = i.thematique_id
                    LEFT JOIN question q ON i.id_question=q.id
                    WHERE t.id = :thiid AND q.is_principale = 1
                )
            ) UNION SELECT distinct q.id
                FROM question q 
                    LEFT JOIN  indicateur i ON i.id_question=q.id
                WHERE ( 
                    q.id in (
                    SELECT q.id FROM question q
                    WHERE q.id in (
                        SELECT q.question_mere_id
                        FROM thematique t
                            LEFT JOIN indicateur i ON t.id = i.thematique_id
                            LEFT JOIN question q ON i.id_question=q.id
                        WHERE t.id = :thiiid
                    ) OR q.id in (
                        SELECT q.id
                        FROM thematique t
                        LEFT JOIN indicateur i ON t.id = i.thematique_id
                        LEFT JOIN question q ON i.id_question=q.id
                        WHERE t.id = :thiiiid AND q.is_principale = 1
                    ) 
                )
            ) 
        )";

    $nv = getParam($sql, array("districtId" => $districtId, "thid" => $thId, "thiid" => $thId, "thiiid" => $thId, "thiiiid" => $thId));
    foreach ($nv as $row) {
        $tp1 = set("DELETE FROM reponse WHERE id  = " . $row["id"], []);
        $reponse = [
            "id" => $row["id"],
            "user_id" => $row["user_id"],
            "date" => $row["date"],
            "question_id" => $row["question_id"],
            "reponse" => $row["reponse"],
            "line_id" => $row["line_id"]
        ];
        add("reponse", $reponse);
    }
    set("DELETE FROM reponse_non_valide 
    WHERE 
        user_id in ( SELECT id FROM user WHERE district_id = :districtId) 
        AND question_id in (
            SELECT distinct q.id
            FROM question q 
                LEFT JOIN  indicateur i ON i.id_question=q.id
            WHERE q.question_mere_id in (
                SELECT q.id FROM question q
                WHERE q.id in (
                    SELECT q.question_mere_id
                    FROM thematique t
                    LEFT JOIN indicateur i ON t.id = i.thematique_id
                    LEFT JOIN question q ON i.id_question=q.id
                    WHERE t.id = :thid
                ) OR q.id in (
                    SELECT q.id
                    FROM thematique t
                    LEFT JOIN indicateur i ON t.id = i.thematique_id
                    LEFT JOIN question q ON i.id_question=q.id
                    WHERE t.id = :thiid AND q.is_principale = 1
                )
            ) UNION SELECT distinct q.id
                FROM question q 
                    LEFT JOIN  indicateur i ON i.id_question=q.id
                WHERE ( 
                    q.id in (
                    SELECT q.id FROM question q
                    WHERE q.id in (
                        SELECT q.question_mere_id
                        FROM thematique t
                            LEFT JOIN indicateur i ON t.id = i.thematique_id
                            LEFT JOIN question q ON i.id_question=q.id
                        WHERE t.id = :thiiid
                    ) OR q.id in (
                        SELECT q.id
                        FROM thematique t
                        LEFT JOIN indicateur i ON t.id = i.thematique_id
                        LEFT JOIN question q ON i.id_question=q.id
                        WHERE t.id = :thiiiid AND q.is_principale = 1
                    ) 
                )
            ) 
        )", array("districtId" => $districtId, "thid" => $thId, "thiid" => $thId, "thiiid" => $thId, "thiiiid" => $thId));
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
    $sql = "select * from utilisateurs where nom='" . $id . "' AND password='" . sha1($pw) . "'";
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