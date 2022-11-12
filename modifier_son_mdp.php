<?php
  session_start();
	//On essaye de se connecter à la base si cela echoue ERREUR
	try {
		$db = new PDO("sqlite:siam.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(Exception $e) {
		die('Erreur : '.$e->getMessage());
	}

  //Si les variables sont définies (grace au formulaire precedent)
  if ( isset($_POST['new_mdp']) && isset($_POST['mdp']) ) {
    //On récupère l'ancien mot de passe
    $stmt = $db->prepare("SELECT mdp FROM users WHERE pseudo = :pseudo");
    $stmt->execute(['pseudo' => $_SESSION['pseudo']]);
    $mdp_hash = $stmt->fetch(PDO::FETCH_NUM);

    //Si c'est le bon mdp
    if ($mdp_hash && password_verify($_POST['mdp'],$mdp_hash[0])) {
      if (strlen($_POST['new_mdp']) >= 4){
        //On verifie si le pseudo est libre
    		$stmt = $db->prepare("UPDATE users SET mdp = :mdp WHERE pseudo = :pseudo");
        $mdp=password_hash($_POST['new_mdp'], PASSWORD_DEFAULT);
        $stmt->bindParam(':pseudo',$_SESSION['pseudo'],PDO::PARAM_STR);
        $stmt->bindParam(':mdp',$mdp,PDO::PARAM_STR);
      	$stmt->execute();
   		 	//On indique a l'admnin que le pseudo existe deja
        echo "<body style='background-color:black;' onLoad='alert(\"Mot de passe modifier\")'>";
      	//Puis on le redirige vers la page creation de joueur
       	echo '<meta http-equiv="refresh" content="0;URL=connecter.php">';
      } else {
        echo "<body style='background-color:black;' onLoad='alert(\"Le nouveau mot de passe doit contenir au moins 4 caractères !\")'>";
        //Puis on le redirige vers la page creation de joueur
        echo '<meta http-equiv="refresh" content="0;URL=modifier_mdp.php">';
      }
		} else {
      echo "<body style='background-color:black;' onLoad='alert(\"Ancien mot de passe incorrecte!\")'>";
      //Puis on le redirige vers la page creation de joueur
      echo '<meta http-equiv="refresh" content="0;URL=modifier_mdp.php">';
    }
  } else {
    echo '<meta http-equiv="refresh" content="0;URL=connecter.php">';
  }
?>