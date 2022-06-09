<?php
require 'dbaccess.php';

function checkName($name, $user) {
    $resp = getParam("SELECT * FROM reponse_non_valide WHERE reponse = :geojson AND user_id = :user", ["geojson"=>$name, "user"=>$user]);
    return $resp == 1;
}

$statusMsg = '';

// File upload path
$user = '';
$targetDir = "layer/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(!empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('geojson');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            // Insert image file name into database
            $insert = checkName($fileName, $user);
            if($insert){
                $statusMsg = "Le fichier ".$fileName. " a bien été télécharger.";
            }else{
                $statusMsg = "Le nom du fichier ne correspond pas à celui inséré dans le canevas";
            } 
        }else{
            $statusMsg = "Une erreur est survenu lors du téléchargement, veuillez réessayer à nouveau. (La copie a été intérrompue)";
        }
    }else{
        $statusMsg = 'Le système n\'accepte que les fichiers de type geoJson, veuillez vérifier l\'extension de votre fichier';
    }
}else{
    $statusMsg = 'Veuillez sélectionner un fichier à télécharger';
}

// Display status message
echo json_encode(["response"=>$statusMsg]);
?>