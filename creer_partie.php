<?php
	session_start();
	if (!isset($_SESSION['pseudo'])) {
		echo 'Les variables ne sont pas déclarées';
		header('Location: index.php');
	}
	include 'partie.php';
	try {
    	$db = new PDO("sqlite:siam.db");
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = $db->query("SELECT id FROM parties ORDER BY id DESC LIMIT 1");
        $id = $id->fetch();
        $id = $id[0]+1;

        echo "Voici l'id : ".$id;

        $plateau=initPlateau();
        $spawn_e=initElephant();
        $spawn_r=initRhino();

        $joueur1 = $_SESSION['pseudo'];
        $joueur2 = "";
        $tour = 0;
        $gagnant = "";
        $plt_jsn = serialize($plateau);
        $e_jsn = serialize($spawn_e);
        $r_jsn = serialize($spawn_r);
        $stmt = $db->prepare("INSERT INTO parties VALUES (:id,:joueur1,:joueur2,:tour,:gagnant,:plateau,:spawnE,:spawnR)");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->bindParam(':joueur1',$joueur1,PDO::PARAM_STR);
        $stmt->bindParam(':joueur2',$joueur2,PDO::PARAM_STR);
        $stmt->bindParam(':tour',$tour,PDO::PARAM_INT);
        $stmt->bindParam(':gagnant',$gagnant,PDO::PARAM_STR);
        $stmt->bindParam(':plateau',$plt_jsn,PDO::PARAM_STR);
        $stmt->bindParam(':spawnE',$e_jsn,PDO::PARAM_STR);
        $stmt->bindParam('spawnR',$r_jsn,PDO::PARAM_STR);
        $res = $stmt->execute();

        if ($res) {
     		 echo '<body style="background-color:blue;" onLoad="alert(\'Partie Crée ! Accédez y depuis le menu --> Jouer dans une partie en cours\')">';
      		echo '<meta http-equiv="refresh" content="0;URL=connecter.php">';
        }

    } catch(Exception $e) {
    	die('Erreur : '.$e->getMessage());
  	}
?>