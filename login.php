<?php
  include 'partie.php';
  function db_existe($pdo,$table) {
    try {
      $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
    } catch (Exception $e) {
      return FALSE;
    }
    return $result !== FALSE;
  }
  //On essaye de se connecter à la base si cela echoue ERREUR
  try {
    $db = new PDO("sqlite:siam.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!db_existe($db,"parties")) {
        $db->query("CREATE TABLE IF NOT EXISTS parties (
          id          int,
          joueur1     VARCHAR,
          joueur2     VARCHAR,
          tour        int,
          gagnant     VARCHAR,
          plateau     VARCHAR,
          spawnE      VARCHAR,
          spawnR      VARCHAR
        );");

        $plateau=initPlateau();
        $spawn_e=initElephant();
        $spawn_r=initRhino();
        $plateau[0][0] = $spawn_e[1];
        $spawn_e[1]=null;
        $plateau[3][2] = $spawn_r[2];
        $spawn_r[2] = null;
        $plateau[3][4] = $spawn_e[5];
        $spawn_e[5]=null;
        $plateau[2][0] = $spawn_r[3];
        $spawn_r[3]=null;

        $id = 1;
        $joueur1 = "admin";
        $joueur2 = "theo";
        $tour = 0;
        $gagnant = "";
        $plt_jsn = serialize($plateau);
        $e_jsn = serialize($spawn_e);
        $r_jsn = serialize($spawn_r);
        $stmt = $db->prepare("INSERT INTO parties VALUES (:id,:joueur1,:joueur2,:tour,:gagnant,:plateau,:spawnE,:spawnR)");
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->bindParam(':joueur1',$joueur1,PDO::PARAM_STR);
        $stmt->bindParam(':joueur2',$joueur2,PDO::PARAM_STR);
        $stmt->bindParam(':tour',$tour,PDO::PARAM_INT);
        $stmt->bindParam(':gagnant',$gagnant,PDO::PARAM_STR);
        $stmt->bindParam(':plateau',$plt_jsn,PDO::PARAM_STR);
        $stmt->bindParam(':spawnE',$e_jsn,PDO::PARAM_STR);
        $stmt->bindParam('spawnR',$r_jsn,PDO::PARAM_STR);
        $stmt->execute();
    }

    if (!db_existe($db,"users")) {
      //Si la table messages n'existe pas on la crée
      $db->query("CREATE TABLE IF NOT EXISTS users (
        pseudo      VARCHAR(20),
        mdp         VARCHAR
      );");

      //On ajoute l'admnistrateur de base admin/admin
      $login="admin";
      $mdp=password_hash($login, PASSWORD_DEFAULT);
      $stmt = $db->prepare("INSERT INTO users VALUES (:pseudo, :mdp)");
      $stmt->bindParam(':pseudo',$login,PDO::PARAM_STR);
      $stmt->bindParam(':mdp',$mdp,PDO::PARAM_STR);
      $stmt->execute();
    }

  } catch(Exception $e) {
    die('Erreur : '.$e->getMessage());
  }

  //Si les variables sont définies (grace au formulaire precedent)
  if ( isset($_POST['pseudo']) && isset($_POST['mdp']) ) {

    $stmt = $db->prepare("SELECT mdp FROM users WHERE pseudo = :pseudo");
    $stmt->execute(['pseudo' => $_POST['pseudo']]);
    $mdp_hash = $stmt->fetch(PDO::FETCH_NUM);

    //Si c'est le bon pseudo et mdp
    if ($mdp_hash && password_verify($_POST['mdp'],$mdp_hash[0])) {
      session_start();//On démarre la session

      //On affecte nos variables de sessions
      $_SESSION['pseudo'] = $_POST['pseudo'];

      //on redirige l'utilisateur sur la page de membre
      header ('location: connecter.php');
    }
    else {
      //On indique a l'utilisateur que ca ne correspond pas
      echo '<body style="background-color:#f8d121;" onLoad="alert(\'Pseudo ou Mot de passe incorrecte !\')">';
      //Puis on le redirige vers la page d'acceuil
      echo '<meta http-equiv="refresh" content="0;URL=index.php">';
    }
  }
  else {
    echo 'Les variables du formulaire ne sont pas déclarées.';
  }
?>
