<?php
	if (!isset($_POST['id']) && !isset($_POST['submit'.$_POST['id']])) {
		header('Location: index.php');
	}

	try {
		$db = new PDO("sqlite:siam.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$partie = $db->prepare("DELETE FROM parties WHERE id=:id");
		$partie->bindParam(":id",$_POST['id'],PDO::PARAM_INT);
		$partie->execute();

		header("Location: supprimer_parties.php");
	} catch(Exception $e) {
    	die('Erreur : '.$e->getMessage());
  	}
?>