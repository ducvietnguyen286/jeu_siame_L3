		--- PROJET SIAM ---

AUTEUR : 
	- Théo GAYANT
	- VIET NGUYEN

A SAVOIR : 
	- Compte Administrateur : admin
		Mot de passe    : admin

	- Autre Compte :
		- theo/theo
		- loli/loli

	-Page d'acceuil : index.php
	-Utilisation de bootstrap (il faut donc avoir acces à internet)

----

> Choix technique :

Utilisateur : pseudo et mot de passe d'au moins 4 caractères

Un utilisateur peut : 
	-Créer une partie
	-Rejoindre une partie aléatoirement
	-Visualiser et rejoindre une partie en cours
	-Visualiser et rejoindre une de ses parties en cours
	-Modifier son mot de passe
	-Se déconnecter

L'administrateur peut :
	-Faire les mêmes actions que les utilisateurs
	-Rejoindre n'importe quelle partie et y jouer
	-Créer un compte joueur
	-Supprimer une partie

Le Jeu : 
	- La partie ne commence pas tant qu'il n'y a q'un seule joueur
	-Le joueur qui c'est le tour peut selectionner un pion de son équipe et on affiche ses possibilités de déplacements (Case Rouge : possibilité de déplacement en fonction de la position du pion choisi | Case Bleu : pion selectionner | Case jaune : la case d'arrivé sélectionner)
	-Si par exemple le joueur valide un coup et que celui ci ne peut pas se réaliser (par exemple avec les poussés) on lui indique
	-Toute les règles du siam sont implémentés
	-Lorsque la partie est terminé : le gagnant est affiché et les joueurs ne peuvent plus rien faire
	-Il faut appuyer sur le bouton "rafraichir" afin de mettre à jour la page du jeu

-----

> Architecture choisie
	
Pour stocker le jeu, il y a trois tableau, le plateau du jeu (5x5), la réserve des éléphants (de taille 5), la réserve des rhinos.
Un pion (elephant,rhino,rocher) est stocké sous la forme d'un tableau avec trois paramètre l'id,le type, et le sens.


-----

> Schéma de la base de donneés :

Nom de la base : siam.db

Tables : 
	users --> permet d'enregistrer les infos utilisateurs
		pseudo (str) -> le pseudo du joueur
		mdp (str)    -> mot de passe crypté du joueur

	parties --> permet d'enregistrer les infos d'une partie
		id (int) -> id de la partie
		joueur1 (str) -> le pseudo du joueur 1
		joueur2 (str) -> le pseudo du joueur 2
		tour    (int) -> le n° du tour
		gagnant (str) -> le pseudo du gagnant
		plateau (str) -> plateau du jeu (serialize)
		spawnE (str)  -> pion en réserve des elephants (serialize)
		spawnR (str)  -> pion en réserve des rhinos (serialize)


	
