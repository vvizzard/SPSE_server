<?php
    session_start();
?>
<?php
	require 'dbaccess.php';
	
	$user = logsOld($_POST["nom"], $_POST["pw"]);
	
	if(isset($user) && isset($user[0])) {
	    $_SESSION["user_id"] = $user[0]["id"];
		header('Location: index.php');
	    // $_SESSION["niveau"] = $user[0]["niveau"];
	    // if($user[0]["niveau"]=="4")header('Location: '.$BASE_URL.'index.php');
	} else {
	    header('Location: login.html');
	}
// 	var_dump($user);
?>