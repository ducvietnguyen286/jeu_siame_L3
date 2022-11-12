<?php
session_start();
	if (!isset($_SESSION['pseudo'])) {
		echo 'Les variables ne sont pas déclarées';
		header('Location: index.html');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<title>Modifier Mot de passe - Projet SIAM</title>
</head>
<body style="background-image: url('./images/fond-modif-mdp.jpg'); background-repeat: no-repeat; background-size: 100% 100%; background-attachment: fixed; ">
	<div class="col-1 ml-5 mt-3 rounded border-dark" style="background-color: white;text-align: center;"><img src="./images/icons/person-fill.svg"><?php echo $_SESSION['pseudo']?></div>
	<div class="container h1 pt-3 text-center" style="color : rgb(145, 32, 229);">Modifier mot de passe</div>
	<div class="container rounded pt-3 pb-3" style="background-color: rgba(145, 32, 229, 0.4);margin-top: 5em;">
		<form action="modifier_son_mdp.php" method="post">
			<div class="form-group">
				<input type="text" name="mdp" class="form-control btn-lg" id="mdp" placeholder="Ancien mot de passe">
			</div>
			<div class="form-group">
				<input type="text" name="new_mdp" class="form-control btn-lg" id="new_mdp" placeholder="Nouveau mot de passe">
				<small class="form-text text-muted"><span style="color:white;">Le mot de passe nécessite au moins 4 caractères</span></small>
			</div>
			<div class="form-group">
				<input type="submit" name="submit" value="Modifier le mot de passe" class="btn btn-warning btn-lg">
			</div>
		</form>
	</div>
	<div class="container">
		<a class="btn btn-danger btn-block btn-lg mb-3 mt-3" style="color : white" href="connecter.php"> <img src="./images/icons/reply-fill.svg" width="3%"> Retour tableau de bord</a>
	</div>
</body>
</html>