<?php
	session_start();
	if (!isset($_SESSION['pseudo'])) {
		echo 'Les variables ne sont pas déclarées';
		header('Location: index.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<link href="./style/autre.css" rel="stylesheet">
	<title>Liste parties - Projet SIAM</title>
</head>
<body class="fond1">
	<div class="container text-center auCentre">
		<?php
			try {
    			$db = new PDO("sqlite:siam.db");
    			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    			$parties = $db->prepare("SELECT id, joueur1, joueur2 FROM parties WHERE joueur2=:joueur2 OR joueur1=:joueur1 ORDER BY id ASC");
    			$parties->bindParam(':joueur1',$_SESSION['pseudo'],PDO::PARAM_STR);
    			$parties->bindParam(':joueur2',$_SESSION['pseudo'],PDO::PARAM_STR);
    			$parties->execute();

    			if ($_SESSION['pseudo'] == "admin") {
    				$parties = $db->query("SELECT id, joueur1, joueur2 FROM parties");
    			}

    			$partie = $parties->fetch(PDO::FETCH_ASSOC);
    			if (!$partie) {
    				echo "<p> Vous n'avez aucune partie en cours actuellement :(</p>";
    			}
    			else {
    				echo "<br><table class='table table-dark table-striped table-bordered table-hover' ><tr><th>ID</th><th>Joueur 1</th><th>Joueur 2</th><th>Jouer</th></tr>";
    				do {
    					echo "<tr><td>".$partie['id']."</td><td>".$partie['joueur1']."</td><td>".$partie['joueur2']."<td><a class='btn-sm btn-warning' href='jeu.php?id=".$partie['id']."'>GO</a></td></tr>";
    				} while ($partie = $parties->fetch(PDO::FETCH_ASSOC));
    				echo "</table>";
    			}
    		} catch(Exception $e) {
    			die('Erreur : '.$e->getMessage());
  			}
		?>
		<a href="connecter.php" class="btn btn-danger">Retour au salon</a>
		<br><br>
	</div>
</body>
</html>