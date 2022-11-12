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
	<title>Creer/Rejoindre Partie - Projet SIAM</title>
</head>
<body class="fond1">
	<div class="container text-center auCentre">
		<form action="creer_partie.php">
			<input class="btn-lg btn-warning" type="submit" name="creer_partie" value="Créer une partie">
		</form>
		<br><br>
		<form action="rejoindre_partie_random.php">
			<input class="btn-lg btn-warning" type="submit" name="creer_partie" value="Rejoindre une partie">
		</form>
		<br><br>
		<a href="connecter.php" class="btn btn-danger">Retour au salon</a>
	</div>
</body>
</html>