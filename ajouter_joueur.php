<?php
	//On essaye de se connecter à la base si cela echoue ERREUR
	try {
		$db = new PDO("sqlite:siam.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(Exception $e) {
		die('Erreur : '.$e->getMessage());
	}

  //Si les variables sont définies (grace au formulaire precedent)
	if ( isset($_POST['pseudo']) && isset($_POST['mdp']) ) {
  	if (strlen($_POST['mdp']) >= 4 && strlen($_POST['pseudo']) >= 4){
  		//On verifie si le pseudo est libre
  		$stmt = $db->prepare("SELECT pseudo FROM users WHERE pseudo = :pseudo");
   		$stmt->execute(['pseudo' => $_POST['pseudo']]);
      $res = $stmt->fetch(PDO::FETCH_NUM);
      if ($res) { // Si il existe déjà
      	//On indique a l'admnin que le pseudo existe deja
   			echo "<body style='background-color:rgb(36, 137, 90);' onLoad='alert(\"Pseudo déjà utilisé !\")'>";
   			//Puis on le redirige vers la page creation de joueur
   			echo '<meta http-equiv="refresh" content="0;URL=creer_joueur.php">';
      }
      else { //Sinon on l'ajoute a la base
      	$mdp=password_hash($_POST['mdp'], PASSWORD_DEFAULT);
		    $stmt = $db->prepare("INSERT INTO users VALUES (:pseudo, :mdp)");
		    $stmt->bindParam(':pseudo',$_POST['pseudo'],PDO::PARAM_STR);
		    $stmt->bindParam(':mdp',$mdp,PDO::PARAM_STR);
		    $stmt->execute();
		    //On indique a l'admnin que l'ajout est reussi
   			echo "<body style='background-color:rgb(36, 137, 90);' onLoad='alert(\"Compté joueur crée !\")'>";
   			//Puis on le redirige vers la page creation de joueur
   			echo '<meta http-equiv="refresh" content="0;URL=connecter.php">';
      }
		}
	  else {
    	//On indique a l'admnin qu'il faut mettre un mot de passe
    	echo "<body style='background-color:rgb(36, 137, 90);' onLoad='alert(\"Veuillez rentrer un pseudo/mot de passe de au moins 4 caractères chacun!\")'>";
    	//Puis on le redirige vers la page d'acceuil
    	echo '<meta http-equiv="refresh" content="0;URL=creer_joueur.php">';
    }
  }
  else {
    echo '<meta http-equiv="refresh" content="0;URL=connecter.php">';
  }
?>