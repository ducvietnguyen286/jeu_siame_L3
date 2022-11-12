<?php
	session_start();
	if (!isset($_SESSION['pseudo'])) {
		echo 'Les variables ne sont pas déclarées';
		header('Location: index.php');
	}

	try {
    	$db = new PDO("sqlite:siam.db");
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    	$partie = $db->prepare("SELECT id FROM parties WHERE joueur2 = '' AND joueur1 != :joueur1 ORDER BY id ASC LIMIT 1");
    	$partie->bindParam(':joueur1',$_SESSION['pseudo'],PDO::PARAM_STR);
    	$partie->execute();
    	$partie = $partie->fetch();
    	if (!$partie) {
      		echo '<body style="background-color:blue;" onLoad="alert(\'Il n y a pas de partie disponible pour le moment, pourquoi pas créer la votre ?\')">';
      		echo '<meta http-equiv="refresh" content="0;URL=creer_rejoindre.php">';
      		exit(0);
    	}

    	$partie = $partie[0];

    	$stmt = $db->prepare("UPDATE parties SET joueur2=:joueur2 WHERE id=:id");
    	$stmt->bindParam(":joueur2",$_SESSION['pseudo'],PDO::PARAM_STR);
    	$stmt->bindParam(":id",$partie,PDO::PARAM_INT);
    	$stmt->execute();

    	header("location: jeu.php?id=".$partie);

    } catch(Exception $e) {
    	die('Erreur : '.$e->getMessage());
  	}
?>