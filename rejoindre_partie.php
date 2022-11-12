<?php
	session_start();
	if (!isset($_SESSION['pseudo'])) {
		echo 'Les variables ne sont pas déclarées';
		header('Location: index.php');
	}
    if(!isset($_POST['id']) && !isset($_POST['submit'.$_POST['id']])) {
        header('Location: index.php');
    }

	try {
    	$db = new PDO("sqlite:siam.db");
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT joueur2 FROM parties WHERE id=:id");
        $stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
        $stmt->execute();
        $j2 = $stmt->fetch(PDO::FETCH_ASSOC);
        $j2 = $j2['joueur2'];
        if ($j2 != "") {
            echo '<body style="background-color:blue;" onLoad="alert(\'Ooops vous avez rencontré une erreur\')">';
            echo '<meta http-equiv="refresh" content="0;URL=liste_parties.php">';
            exit(0);
        }

    	$stmt = $db->prepare("UPDATE parties SET joueur2=:joueur2 WHERE id=:id");
    	$stmt->bindParam(":joueur2",$_SESSION['pseudo'],PDO::PARAM_STR);
    	$stmt->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
    	$res = $stmt->execute();

        if (!$res) {
            echo '<body style="background-color:blue;" onLoad="alert(\'Ooops vous avez rencontré une erreur\')">';
            echo '<meta http-equiv="refresh" content="0;URL=liste_parties.php">';
            exit(0);
        }
    	header("location: jeu.php?id=".$partie['id']);

    } catch(Exception $e) {
    	die('Erreur : '.$e->getMessage());
  	}
?>