<?php
session_start();

//On détruit les variables de notre session
session_unset();

//On detruit notre session
session_destroy();

//On redirige le visiteur vers la page d'acceuil
header ('location: index.php');
?>