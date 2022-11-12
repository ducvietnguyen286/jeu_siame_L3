<?php
  session_start();
  if (isset($_SESSION['pseudo'])) {
    header('Location: connecter.php');
  }
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8"/>
    <meta name="author" content="Théo Gayant, Viet Nguyen">
    <link href="./style/index.css" rel="stylesheet">
    <title>Connexion - Projet SIAM</title>
  </head>
  <body>
    <form id="form-connexion" action="login.php" method="post">

      <img id="logo" src="./images/logo_sia.gif" alt="logo" width="200" height="50">

      <h1>Connexion</h1>

      <label class="mesLabels" for="pseudo">Pseudo</label>
      <input type="text" name="pseudo" class="form-input" placeholder="Pseudo" maxlength="20" />

      <br>

      <label class="mesLabels" for="mdp">Mot de passe</label>
      <input type="password" name="mdp" class="form-input" placeholder="Mot de passe" />

      <br>

      <input type="submit" name="submit" class="form-submit" value="Se connecter" />

    </form>
    <br>
    <img id="cover" src="./images/cover-siam.png" width=400 height="300">
  </body>
</html>