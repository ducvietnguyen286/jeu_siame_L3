<?php
	include 'partie.php';
	if (!isset($_POST['submit'])) {

	}
	$lig_dep = $_POST['lig_depart'];
	$col_dep = $_POST['col_depart'];
	$lig_arr = $_POST['lig_arrive'];
	$col_arr = $_POST['col_arrive'];
	$sens = $_POST['rotation'];

	try {
    	$db = new PDO("sqlite:siam.db");
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    	$id = $_POST['id'];

    	$stmt = $db->prepare("SELECT * FROM parties where id = :id");
    	$stmt->bindParam(':id',$id,PDO::PARAM_INT);
    	$stmt->execute();

    	$partie = $stmt->fetch(PDO::FETCH_ASSOC);
    	$plateau = unserialize($partie['plateau']);
  		$spawn_e = unserialize($partie['spawnE']);
  		$spawn_r = unserialize($partie['spawnR']);
      $tour = $partie['tour'];
	} catch(Exception $e) {
    	die('Erreur : '.$e->getMessage());
  }
  	if ($tour != $_POST['tour_actu']) {
      echo '<body style="background-color:#f8d121;" onLoad="alert(\'Hep hep hep pas de triche !\')">';
      //Puis on le redirige vers la page d'acceuil
      echo '<meta http-equiv="refresh" content="0;URL=jeu.php?id='.$partie['id'].'">';
    } else {

      $unGagnant = "";

    	if (majCoup($plateau,$spawn_e,$spawn_r,$lig_dep,$col_dep,$lig_arr,$col_arr,$sens,$unGagnant)) {
        $plateau = serialize($plateau);
        $spawn_e = serialize($spawn_e);
        $spawn_r = serialize($spawn_r);

        $stmt = $db->prepare("UPDATE parties SET plateau = :plateau, spawnE=:spawnE, spawnR=:spawnR, tour=:tour, gagnant=:gagnant WHERE id=:id");
        $stmt->bindParam(":id",$id,PDO::PARAM_INT);
        $stmt->bindParam(":plateau",$plateau,PDO::PARAM_STR);
        $stmt->bindParam(":spawnE",$spawn_e,PDO::PARAM_STR);
        $stmt->bindParam(":spawnR",$spawn_r,PDO::PARAM_STR);
        $tour+=1;
        $stmt->bindParam(":tour",$tour,PDO::PARAM_INT);
        $stmt->bindParam(":gagnant",$unGagnant,PDO::PARAM_STR);
        $stmt->execute();

        header ('location: jeu.php?id='.$partie["id"]);
      }
      else {
        //On indique a l'utilisateur que ca ne correspond pas
        echo '<body style="background-color:#f8d121;" onLoad="alert(\'Le déplacement que vous voulez effectuer n est pas possible !\nEssayer autre chose ;)\')">';
        //Puis on le redirige vers la page d'acceuil
        echo '<meta http-equiv="refresh" content="0;URL=jeu.php?id='.$partie['id'].'">';
      }
    }
?>