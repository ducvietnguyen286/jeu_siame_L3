<?php
	session_start();
	if (!isset($_SESSION['pseudo'])) {
		echo 'Les variables ne sont pas déclarées';
		header('Location: index.php');
	}

	function creer_container($class,$lien,$icon,$valeur) {
		echo '<div class="container">';
		echo '<a class="'.$class.'" style="color : white" href="'.$lien.'"> <img src="'.$icon.'" width="3%"> '.$valeur.'</a>';
		echo '</div>';
	}

	function creer_container_admin() {
		creer_container("btn btn-info btn-block btn-lg mb-3","creer_joueur.php","./images/icons/tools.svg","Créer un compte joueur");
		creer_container("btn btn-danger btn-block btn-lg mb-3","supprimer_parties.php","./images/icons/trash-fill.svg","Supprimer une partie");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<title>Acceuil - Projet SIAM</title>
</head>
<body style="background-image: url('./images/cover-siam__.png'); background-repeat: no-repeat; background-size: 100% 100%; background-attachment: fixed; ">
	<div class="col-1 ml-5 mt-3 rounded border-dark" style="background-color: white;text-align: center;"><img src="./images/icons/person-fill.svg"><?php echo $_SESSION['pseudo']?></div>
	<div class="container">
		<a type="button" class="btn btn-primary btn-block btn-lg mt-3 mb-3" style="color : white" href="creer_rejoindre.php"><img src="./images/icons/map.svg" width="3%"> Créer / Rejoindre une partie en attente</a>
	</div>
	<div class="container">
		<a class="btn btn-warning btn-block btn-lg mb-3" style="color : white" href="liste_parties.php"><img src="./images/icons/list-ul.svg" width="3%"> Visualiser la liste des parties à rejoindre</a>
	</div>
	<div class="container">
		<a class="btn btn-success btn-block btn-lg mb-3" style="color : white" href="liste_parties_en_cours.php"><img src="./images/icons/controller.svg" width="3%"> Visualiser et jouer dans vos parties en cours</a>
	</div>
	<div class="container">
		<a class="btn btn-danger btn-block btn-lg mb-3" style="color : white" href="modifier_mdp.php"> <img src="./images/icons/gear-fill.svg" width="3%"> Modifier le mot de passe</a>
	</div>
	<div class="container">
		<a class="btn btn-danger btn-block btn-lg mb-3" style="color : white" href="se_deconnecter.php"> <img src="./images/icons/reply-fill.svg" width="3%"> Se déconnecter</a>
	</div>
	<?php
		if($_SESSION['pseudo'] == "admin") {
			creer_container_admin();
		}
	?>

</body>
</html>