<?php
session_start();
if ($_SESSION['pseudo'] != "admin") {
	header('Location: connecter.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<title>Creer un joueur - Projet SIAM</title>
</head>
<body style="background-image: url('./images/fond-cc.jpg'); background-repeat: no-repeat; background-size: 100% 100%; background-attachment: fixed; " >
	<div class="container h1 pt-3 text-center" style="color : rgb(36, 137, 90);">Créer un compte joueur</div>
	<div class="container rounded pt-3 pb-3" style="background-color: rgba(36, 137, 90, 0.75);margin-top: 5em;">
		<form action="ajouter_joueur.php" method="post">
			<div class="form-group">
				<input type="text" name="pseudo" class="form-control btn-lg" id="pseudo" placeholder="Pseudo" maxlength="20">
				<small class="form-text text-muted"><span style="color:white;">Pseudo limité à 20 caractères</span></small>
			</div>
			<div class="form-group">
				<input type="text" name="mdp" class="form-control btn-lg" id="mdp" placeholder="Mot de passe">
			</div>
			<div class="form-group">
				<input type="submit" name="submit" value="Créer le compte" class="btn btn-warning btn-lg">
			</div>
		</form>
	</div>
	<div class="container">
		<a class="btn btn-danger btn-block btn-lg mb-3 mt-3" style="color : white" href="connecter.php"> <img src="./images/icons/reply-fill.svg" width="3%"> Retour tableau de bord</a>
	</div>
</body>
</html>