<?php
	session_start();

	include 'partie.php';

	if (!isset($_SESSION['pseudo'])) {
		header('Location: index.php');
	}
	try {
    	$db = new PDO("sqlite:siam.db");
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    	$id = htmlspecialchars($_GET["id"]);

    	$stmt = $db->prepare("SELECT * FROM parties where id = :id");
    	$stmt->bindParam(':id',$id,PDO::PARAM_INT);
    	$res = $stmt->execute();

    	if (!$res) {
    		header('Location: connecter.php');
    	}

    	$partie = $stmt->fetch(PDO::FETCH_ASSOC);

    	if(!$partie) {
    		echo '<body style="background-color:yellow;" onLoad="alert(\'Partie inexistante\')">';
      		echo '<meta http-equiv="refresh" content="0;URL=connecter.php">';
      		exit(0);
    	}

    	if ($partie['joueur2'] == "") {
      		echo '<body style="background-color:yellow;" onLoad="alert(\'Il manque un joueur, il faut attendre !\')">';
      		echo '<meta http-equiv="refresh" content="0;URL=connecter.php">';
      		exit(0);
    	}

    	//Recup du joueur qui a le droit de jouer
    	if ($partie['tour'] % 2 == 0) {
    		$joueurActif = $partie['joueur1'];
    		$equipe = "elephant";
    	}
    	else {
    		$joueurActif = $partie['joueur2'];
    		$equipe = "rhino";
    	}

    	//On vérifie si la partie correspond bien au joueurs
    	if (($_SESSION['pseudo'] != $partie['joueur1'] && $_SESSION['pseudo'] != $partie['joueur2']) && $_SESSION['pseudo'] != "admin") {
    		header('Location: connecter.php');
    	}



	} catch(Exception $e) {
    	die('Erreur : '.$e->getMessage());
  	}
  	$plateau = unserialize($partie['plateau']);
  	$spawn_e = unserialize($partie['spawnE']);
  	$spawn_r = unserialize($partie['spawnR']);
  	$sonTour = ($_SESSION['pseudo'] == $joueurActif) || ($_SESSION['pseudo'] == "admin");
  	if ($partie['gagnant'] != "") {
  		$sonTour = false;
  	}
	//deplacer($plateau[0][0],0,1,$plateau);

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="jeu.js"></script>
	<link href="./style/plateau.css" rel="stylesheet">
	<title>Jeu - Projet SIAM</title>
</head>
<body>
	<div class="haut" style="background-color: blue;">
		<table border="0" class="haut">
			<tr><td><div id="e1" class="case_pion <?php echo majSpawnElephant($spawn_e,1) ?>" onClick=<?php if ($sonTour) appelFonctionSpawn($spawn_e,1,"e",$equipe) ?>></div></td>
				<td><div id="e2" class="case_pion <?php echo majSpawnElephant($spawn_e,2) ?>" onClick=<?php if ($sonTour) appelFonctionSpawn($spawn_e,2,"e",$equipe) ?>></div></td>
				<td><div id="e3" class="case_pion <?php echo majSpawnElephant($spawn_e,3) ?>" onClick=<?php if ($sonTour) appelFonctionSpawn($spawn_e,3,"e",$equipe) ?>></div></td>
				<td><div id="e4" class="case_pion <?php echo majSpawnElephant($spawn_e,4) ?>" onClick=<?php if ($sonTour) appelFonctionSpawn($spawn_e,4,"e",$equipe) ?>></div></td>
				<td><div id="e5" class="case_pion <?php echo majSpawnElephant($spawn_e,5) ?>" onClick=<?php if ($sonTour)  appelFonctionSpawn($spawn_e,5,"e",$equipe) ?>></div></td>
			</tr>
		</table>
	</div>
	<div class="plateau" style="background-color:yellow;">
		<table border="0" background="./images/grille.jpg">
		  <tr>
		    <td><div id="00" class="case <?php echo majPlateau($plateau,0,0) ?>" onClick=<?php if ($sonTour) appelFonction(0,0,$plateau,$equipe) ?>></div></td>
		    <td><div id="01" class="case <?php echo majPlateau($plateau,0,1) ?>" onClick=<?php if ($sonTour) appelFonction(0,1,$plateau,$equipe) ?>></div></td>
		    <td><div id="02" class="case <?php echo majPlateau($plateau,0,2) ?>" onClick=<?php if ($sonTour) appelFonction(0,2,$plateau,$equipe) ?>></div></td>
		    <td><div id="03" class="case <?php echo majPlateau($plateau,0,3) ?>" onClick=<?php if ($sonTour) appelFonction(0,3,$plateau,$equipe) ?>></div></td>
		    <td><div id="04" class="case <?php echo majPlateau($plateau,0,4) ?>" onClick=<?php if ($sonTour) appelFonction(0,4,$plateau,$equipe) ?>></div></td>
		  </tr>
		  <tr>
		    <td><div id="10" class="case <?php echo majPlateau($plateau,1,0) ?>" onClick=<?php if ($sonTour) appelFonction(1,0,$plateau,$equipe) ?>></div></td>
		    <td><div id="11" class="case <?php echo majPlateau($plateau,1,1) ?>" onClick=<?php if ($sonTour) appelFonction(1,1,$plateau,$equipe) ?>></div></td>
		    <td><div id="12" class="case <?php echo majPlateau($plateau,1,2) ?>" onClick=<?php if ($sonTour) appelFonction(1,2,$plateau,$equipe) ?>></div></td>
		    <td><div id="13" class="case <?php echo majPlateau($plateau,1,3) ?>" onClick=<?php if ($sonTour) appelFonction(1,3,$plateau,$equipe) ?>></div></td>
		    <td><div id="14" class="case <?php echo majPlateau($plateau,1,4) ?>" onClick=<?php if ($sonTour) appelFonction(1,4,$plateau,$equipe) ?>></div></td>
		  </tr>
		  <tr>
		    <td><div id="20" class="case <?php echo majPlateau($plateau,2,0) ?>" onClick=<?php if ($sonTour) appelFonction(2,0,$plateau,$equipe) ?>></div></td>
		    <td><div id="21" class="case <?php echo majPlateau($plateau,2,1) ?>" onClick=<?php if ($sonTour) appelFonction(2,1,$plateau,$equipe) ?>></div></td>
		    <td><div id="22" class="case <?php echo majPlateau($plateau,2,2) ?>" onClick=<?php if ($sonTour) appelFonction(2,2,$plateau,$equipe) ?>></div></td>
		    <td><div id="23" class="case <?php echo majPlateau($plateau,2,3) ?>" onClick=<?php if ($sonTour) appelFonction(2,3,$plateau,$equipe) ?>></div></td>
		    <td><div id="24" class="case <?php echo majPlateau($plateau,2,4) ?>" onClick=<?php if ($sonTour) appelFonction(2,4,$plateau,$equipe) ?>></div></td>
		  </tr>
		  <tr>
		    <td><div id="30" class="case <?php echo majPlateau($plateau,3,0) ?>" onClick=<?php if ($sonTour) appelFonction(3,0,$plateau,$equipe) ?>></div></td>
		    <td><div id="31" class="case <?php echo majPlateau($plateau,3,1) ?>" onClick=<?php if ($sonTour) appelFonction(3,1,$plateau,$equipe) ?>></div></td>
		    <td><div id="32" class="case <?php echo majPlateau($plateau,3,2) ?>" onClick=<?php if ($sonTour) appelFonction(3,2,$plateau,$equipe) ?>></div></td>
		    <td><div id="33" class="case <?php echo majPlateau($plateau,3,3) ?>" onClick=<?php if ($sonTour) appelFonction(3,3,$plateau,$equipe) ?>></div></td>
		    <td><div id="34" class="case <?php echo majPlateau($plateau,3,4) ?>" onClick=<?php if ($sonTour) appelFonction(3,4,$plateau,$equipe) ?>></div></td>
		  </tr>
		  <tr>
		    <td><div id="40" class="case <?php echo majPlateau($plateau,4,0) ?>" onClick=<?php if ($sonTour) appelFonction(4,0,$plateau,$equipe) ?>></div></td>
		    <td><div id="41" class="case <?php echo majPlateau($plateau,4,1) ?>" onClick=<?php if ($sonTour) appelFonction(4,1,$plateau,$equipe) ?>></div></td>
		    <td><div id="42" class="case <?php echo majPlateau($plateau,4,2) ?>" onClick=<?php if ($sonTour) appelFonction(4,2,$plateau,$equipe) ?>></div></td>
		    <td><div id="43" class="case <?php echo majPlateau($plateau,4,3) ?>" onClick=<?php if ($sonTour) appelFonction(4,3,$plateau,$equipe) ?>></div></td>
		    <td><div id="44" class="case <?php echo majPlateau($plateau,4,4) ?>" onClick=<?php if ($sonTour) appelFonction(4,4,$plateau,$equipe) ?>></div></td>
		  </tr>
		</table>
	</div>
	<div class="bas" style="background-color: green;">
		<table border="0" class="bas">
			<tr><td><div id="r1" class="case_pion <?php echo majSpawnRhino($spawn_r,1) ?>" onClick=<?php if ($sonTour) appelFonctionSpawn($spawn_r,1,"r",$equipe) ?>></div></td>
				<td><div id="r2" class="case_pion <?php echo majSpawnRhino($spawn_r,2) ?>" onClick=<?php if ($sonTour) appelFonctionSpawn($spawn_r,2,"r",$equipe) ?>></div></td>
				<td><div id="r3" class="case_pion <?php echo majSpawnRhino($spawn_r,3) ?>" onClick=<?php if ($sonTour) appelFonctionSpawn($spawn_r,3,"r",$equipe) ?>></div></td>
				<td><div id="r4" class="case_pion <?php echo majSpawnRhino($spawn_r,4) ?>" onClick=<?php if ($sonTour) appelFonctionSpawn($spawn_r,4,"r",$equipe) ?>></div></td>
				<td><div id="r5" class="case_pion <?php echo majSpawnRhino($spawn_r,5) ?>" onClick=<?php if ($sonTour) appelFonctionSpawn($spawn_r,5,"r",$equipe) ?>></div></td>
			</tr>
		</table>
	</div>
	<div class="resteenbas" >
		<?php 
			if ($partie["gagnant"] == "") {
				echo "<p>C'est le tour de : <strong>".$joueurActif."</strong></p>";
			} else {
				echo "<p>Partie Terminé le gagnant est : ";
				if ($partie["gagnant"] == "rhino") {
					echo "<strong>".$partie["joueur2"]."</strong></p>";
				} else {
					echo "<strong>".$partie["joueur2"]."</strong></p>";
				}
			}
		?>
		<button class="btn btn-warning" onClick="window.location.reload(false)"> Rafraichir </button>
		<?php if ($sonTour)  {
			echo '<form action="envoie_coup.php" method="POST">';
			echo '<input type="hidden" name="lig_depart" id="lig_depart" value="-1" autocomplete="off"/>';
			echo '<input type="hidden" name="col_depart" id="col_depart" value="-1" autocomplete="off"/>';
			echo '<input type="hidden" name="lig_arrive" id="lig_arrive" value="-1" autocomplete="off"/>';
			echo '<input type="hidden" name="col_arrive" id="col_arrive" value="-1" autocomplete="off"/>';
			echo '<input type="hidden" name="rotation" id="rotation" value="-1" autocomplete="off"/><br>';
			echo '<input type="button" class="btn btn-info" onClick="tournerCarte();" name="changer_sens" id="changer_sens" value="Tourner le sens"/>';
			echo '  <input type="submit" class="btn btn-info" id="btn_valider" name="submit" value="Valider votre coup" disabled="true" />';
			echo "<input type='hidden' name='tour_actu' value=".$partie['tour']." autocomplete='off'>";
			echo "<input type='hidden' name='id' value=".$partie['id']." autocomplete='off'>";
		echo '</form>'; }
		echo "<br><br>";?>
		<a href="liste_parties_en_cours.php" class="btn btn-danger">Retour a la liste des parties en cours</a>
		<a href="connecter.php" class="btn btn-danger">Retour au salon</a>
	</div>
</body>
</html>