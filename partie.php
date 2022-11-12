<?php
	include 'pion.php';
	function initElephant() {
		$tbl = array();
		for($i=1;$i<=5;$i++) {
			$tbl[$i]= (new pion($i,ELEPHANT,HAUT))->getArray();
		}
		return $tbl;
	}
	function initRhino() {
		$tbl = array();
		for($i=1;$i<=5;$i++) {
			$tbl[$i]= (new pion($i,RHINO,HAUT))->getArray();
		}
		return $tbl;
	}
	function initRocher() {
		$tbl = array();
		for($i=1;$i<=3;$i++){
			$tbl[$i]= (new pion($i,ROCHER,HAUT))->getArray();
		}
		return $tbl;
	}
	function initPlateau() {
		$tbl = array();
		$roc = initRocher();
		for($i=0;$i<5;$i++) {
			$ligne = array();
			$ligne[0] = NULL;
			$ligne[1] = NULL;
			$ligne[2] = NULL;
			$ligne[3] = NULL;
			$ligne[4] = NULL;
			if ($i == 2) {
				$ligne[1] = $roc[1];
				$ligne[2] = $roc[2];
				$ligne[3] = $roc[3];
			}
			$tbl[$i] = $ligne;
		}
		return $tbl;
	}

	function majSpawnElephant($tblSpawn,$id) {
		if (!is_null($tblSpawn[$id])) {
			return "e".$tblSpawn[$id]['id']." ".majSens($tblSpawn[$id]);
		}
		return "";
	}

	function majSpawnRhino($tblSpawn,$id) {
		if (!is_null($tblSpawn[$id])) {
			return "r".$tblSpawn[$id]['id']." ".majSens($tblSpawn[$id]);
		}
		return "";
	}
	function majSens($n) {
		if ($n['sens'] == HAUT) {
			return "rota_haut";
		}
		else if ($n['sens'] == DROITE) {
			return "rota_droite";
		}
		else if ($n['sens'] == BAS) {
			return "rota_bas";
		}
		else if ($n['sens'] == GAUCHE) {
			return "rota_gauche";
		}
	}
	function majPlateau($plt,$lig,$col) {
		if (!is_null($plt[$lig][$col])) {
			$n=$plt[$lig][$col];
			if ($n['type'] == ELEPHANT) {
				return "e".$n['id']." ".majSens($n);
			}
			else if ($n['type'] == RHINO) {
				return "r".$n['id']." ".majSens($n);
			}
			else if ($n['type'] == ROCHER) {
				return "c".$n['id']." ".majSens($n);
			}
		}
	}
	function deplacer($lig,$col,$new_lig,$new_col,&$plateau,&$spawn_e,&$spawn_r,$sens,&$unGagnant) {
		$n = recupCase($lig,$col,$plateau,$spawn_e,$spawn_r);
		$m = recupCase($new_lig,$new_col,$plateau,$spawn_e,$spawn_r);
		//Si on va sur une case vide
		if (is_null($m)) {
			if ($new_lig != -2 && $new_lig != -3) {
				$plateau[$new_lig][$new_col] = $n;
			}
			else if ($new_lig == -2) {
				$spawn_e[$new_col] = $n;
			}
			else if ($new_lig == -3) {
				$spawn_r[$new_col] = $n;
			}
			resetCase($lig,$col,$plateau,$spawn_e,$spawn_r);
			return true;
		}
		//Sinon si on se déplace sur un case occupé
		else {
			if ($lig < -1) {
					$laLig = $new_lig;
					$laCol = $new_col;//la colonne ou on insert
				} else {
					$laLig = $lig;
					$laCol = $col;
				}
			//Si on se déplace vers le haut
			if($laLig - $new_lig > 0 || ($lig < -1 && $new_lig == 4 && $n['sens'] == "haut")) {
				//Si il n'est pas du même sens que le sens de poussé
				if(!estDuBonSens($lig,$col,$plateau,$spawn_e,$spawn_r,"haut")) {
					return false;
				}
				$nbPour = 0;
				$nbLast = 1;
				$nbRocher = 0;
				$arrive = 0;
				if ($lig < -1) {
					$laLig = $new_lig;
					$laCol = $new_col;//la colonne ou on insert
				} else {
					$laLig = $lig;
					$laCol = $col;
				}
				for($i=0;$i<=$laLig;$i++) {
					if ($plateau[$i][$laCol] != null) {
						//Si c'est un rocher
						if ($plateau[$i][$laCol]['type'] == "rocher") {
							$nbRocher+=1;
						}
						//Si c'est du meme sens que le sens de pousser 
						else if ($plateau[$i][$laCol]['sens'] == "haut" ) {
							$nbPour+=1;
							$nbLast+=1;
						}
						//Sinon si c'est contre le sens de pousser 
						else if ($plateau[$i][$laCol]['sens'] == "bas") {
							$nbPour-=1;
							$nbLast=-1;
						}
					} else {
						$nbPour = 0;
						$nbLast = 1;
						$nbRocher = 0;
						$arrive = $i;
					}
				}
				//Si le pion qu'on ajoute est au spawn il faut le rajouter dans la possée
				if ($lig < -1) {
					$nbPour+=1;
					$nbLast+=1;
					$deb = 4; //la ligne ou on insert
				} else {
					$deb = $lig;
				}
				if ($nbLast <= 0) {
					return false;
				}
				if ($nbPour > 0 && $nbPour >= $nbRocher) {
					$gg = false;
					for($i=$arrive;$i<$deb;$i++) {
						$n = $plateau[$i][$laCol];
						//Si on pousse un pion en dehors du plateau
						if($i == 0 && $n != null) {
							if ($n['type'] == "elephant") {
								$spawn_e[$n['id']] = $n;
							} else if ($n['type'] == "rhino") {
								$spawn_r[$n['id']] = $n;
							} else if ($n['type'] == "rocher") {
								$gg = true;
							}
							$plateau[$i][$laCol] = null;
						}
						if ($i != 4) {
							$plateau[$i][$laCol] = $plateau[$i+1][$laCol];
							$plateau[$i+1][$laCol] = null;
							if ($gg && $plateau[$i][$laCol] != null && $plateau[$i][$laCol]['type'] != "rocher" && $plateau[$i][$laCol]['sens'] == "haut") {
								$gg = false;
								$unGagnant = $plateau[$i][$laCol]['type'];
							}
						}
						else {
							$plateau[$i][$laCol] = null;
						}
					}
					if ($lig == -2) {
						$plateau[4][$laCol] = $spawn_e[$col];
						$spawn_e[$col] = null;
					}
					else if ($lig == -3) {
						$plateau[4][$laCol] = $spawn_r[$col];
						$spawn_r[$col] = null;
					}
					return true;
				}
				else {
					return false;
				}
			}
			//Si on se déplace vers le bas
			else if ($laLig - $new_lig < 0 || ($lig < -1 && $new_lig == 0 && $n['sens'] == "bas")) {
				//Si il n'est pas du même sens que le sens de poussé
				if(!estDuBonSens($lig,$col,$plateau,$spawn_e,$spawn_r,"bas")) {
					return false;
				}
				$nbPour = 0;
				$nbLast = 1;
				$nbRocher = 0;
				$arrive = 4;
				if ($lig < -1) {
					$laCol = $new_col;//la colonne ou on insert
				} else {
					$laCol = $col;
				}
				for($i=4;$i>=$lig;$i--) {
					if ($plateau[$i][$laCol] != null) {
						//Si c'est un rocher
						if ($plateau[$i][$laCol]['type'] == "rocher") {
							$nbRocher+=1;
						}
						//Si c'est du meme sens que le sens de pousser 
						else if ($plateau[$i][$laCol]['sens'] == "bas" ) {
							$nbPour+=1;
							$nbLast+=1;
						}
						//Sinon si c'est contre le sens de pousser 
						else if ($plateau[$i][$laCol]['sens'] == "haut") {
							$nbPour-=1;
							$nbLast = -1;
						}
					} else {
						$nbPour = 0;
						$nbRocher = 0;
						$arrive = $i;
						$nbLast = 1;
					}
					if ($i == 0) break;
				}
				//Si le pion qu'on ajoute est au spawn il faut le rajouter dans la possée
				if ($lig < -1) {
					$nbPour+=1;
					$deb = 0; //la ligne ou on insert
				} else {
					$deb = $lig;
				}
				if ($nbLast <= 0) {
					return false;
				}
				if ($nbPour > 0 && $nbPour >= $nbRocher) {
					$gg = false;
					for($i=$arrive;$i>$deb;$i--) {
						$n = $plateau[$i][$laCol];
						//Si on pousse un pion en dehors du plateau
						if($i == 4 && $n != null) {
							if ($n['type'] == "elephant") {
								$spawn_e[$n['id']] = $n;
							} else if ($n['type'] == "rhino") {
								$spawn_r[$n['id']] = $n;
							} else if ($n['type'] == "rocher") {
								$gg = true;
							}
							$plateau[$i][$col] = null;
						}
						if ($i != 0) {
							$plateau[$i][$laCol] = $plateau[$i-1][$laCol];
							$plateau[$i-1][$laCol] = null;
							if ($gg && $plateau[$i][$laCol] != null && $plateau[$i][$laCol]['type'] != "rocher" && $plateau[$i][$laCol]['sens'] == "bas") {
								$gg = false;
								$unGagnant = $plateau[$i][$laCol]['type'];
							}
						}
						else {
							$plateau[$i][$laCol] = null;
						}
					}
					if ($lig == -2) {
						$plateau[0][$laCol] = $spawn_e[$col];
						$spawn_e[$col] = null;
					}
					else if ($lig == -3) {
						$plateau[0][$laCol] = $spawn_r[$col];
						$spawn_r[$col] = null;
					}
					return true;
				}
				else {
					return false;
				}
			}
			//Si on se déplace vers la gauche
			else if ($laCol - $new_col > 0 || ($lig < -1 && $new_col == 4)) {
				//Si il n'est pas du même sens que le sens de poussé
				if(!estDuBonSens($lig,$col,$plateau,$spawn_e,$spawn_r,"gauche")) {
					return false;
				}
				$nbPour = 0;
				$nbLast = 1;
				$nbRocher = 0;
				$arrive = 0;
				if ($lig < -1) {
					$laLig = $new_lig;
					$laCol = $new_col;
				} else {
					$laLig = $lig;
					$laCol = $col;
				}
				for($i=0;$i<=$laCol;$i++) {
					if ($plateau[$laLig][$i] != null) {
						//Si c'est un rocher
						if ($plateau[$laLig][$i]['type'] == "rocher") {
							$nbRocher+=1;
						}
						//Si c'est du meme sens que le sens de pousser 
						else if ($plateau[$laLig][$i]['sens'] == "gauche" ) {
							$nbPour+=1;
							$nbLast+=1;
						}
						//Sinon si c'est contre le sens de pousser 
						else if ($plateau[$laLig][$i]['sens'] == "droite") {
							$nbPour-=1;
							$nbLast=-1;
						}
					} else {
						$nbPour = 0;
						$nbLast = 1;
						$nbRocher = 0;
						$arrive = $i;
					}
				}
				if ($lig < -1) {
					$nbPour+=1;
					$nbLast+=1;
					$deb = 4;
				} else {
					$deb = $col;
				}
				if ($nbLast <= 0) {
					return false;
				}
				if ($nbPour > 0 && $nbPour >= $nbRocher) {
					$gg =false;
					for($i=$arrive;$i<$deb;$i++) {
						$n = $plateau[$laLig][$i];
						//Si on pousse un pion en dehors du plateau
						if($i == 0 && $n != null) {
							if ($n['type'] == "elephant") {
								$spawn_e[$n['id']] = $n;
							} else if ($n['type'] == "rhino") {
								$spawn_r[$n['id']] = $n;
							} else if ($n['type'] == "rocher") {
								$gg = true;
							}
							$plateau[$laLig][$i] = null;
						}
						if ($i != 4) {
							$plateau[$laLig][$i] = $plateau[$laLig][$i+1];
							$plateau[$laLig][$i+1] = null;
							if ($gg && $plateau[$laLig][$i] != null && $plateau[$laLig][$i]['type'] != "rocher" && $plateau[$laLig][$i]['sens'] == "gauche") {
								$unGagnant = $plateau[$laLig][$i]['type'];
								$gg = false;
							}
						}
						else {
							$plateau[$laLig][$i] = null;
						}
					}
					if ($lig == -2) {
						$plateau[$laLig][4] = $spawn_e[$col];
						$spawn_e[$col] = null;
					}
					else if ($lig == -3) {
						$plateau[$laLig][4] = $spawn_r[$col];
						$spawn_r[$col] = null;
					}
					return true;
				}
				else {
					return false;
				}
			}
			//Si on se déplace vers le droite
			else if ($laCol - $new_col < 0 || ($lig < -1 && $new_col == 0)) {
				//Si il n'est pas du même sens que le sens de poussé
				if(!estDuBonSens($lig,$col,$plateau,$spawn_e,$spawn_r,"droite")) {
					return false;
				}
				$nbPour = 0;
				$nbLast = 1;
				$nbRocher = 0;
				$arrive = 4;
				if ($lig < -1) {
					$laLig = $new_lig;
					$laCol = $new_col;
				} else {
					$laLig = $lig;
					$laCol = $col;
				}
				for($i=4;$i>=$laCol;$i--) {
					if ($plateau[$laLig][$i] != null) {
						//Si c'est un rocher
						if ($plateau[$laLig][$i]['type'] == "rocher") {
							$nbRocher+=1;
						}
						//Si c'est du meme sens que le sens de pousser 
						else if ($plateau[$laLig][$i]['sens'] == "droite" ) {
							$nbPour+=1;
							$nbLast+=1;
						}
						//Sinon si c'est contre le sens de pousser 
						else if ($plateau[$laLig][$i]['sens'] == "gauche") {
							$nbPour-=1;
							$nbLast=-1;
						}
					} else {
						$nbPour = 0;
						$nbLast = 1;
						$nbRocher = 0;
						$arrive = $i;
					}
				}
				if ($lig < -1) {
					$nbPour+=1;
					$nbLast+=1;
					$deb = 0;
				} else {
					$deb = $col;
				}
				if ($nbLast <= 0) {
					return false;
				}
				if ($nbPour > 0 && $nbPour >= $nbRocher) {
					$gg = false;
					for($i=$arrive;$i>$deb;$i--) {
						$n = $plateau[$laLig][$i];
						//Si on pousse un pion en dehors du plateau
						if($i == 4 && $n != null) {
							if ($n['type'] == "elephant") {
								$spawn_e[$n['id']] = $n;
							} else if ($n['type'] == "rhino") {
								$spawn_r[$n['id']] = $n;
							} else if ($n['type'] == "rocher") {
								$gg = true;
							}
							$plateau[$laLig][$i] = null;
						}
						if ($i != 0) {
							$plateau[$laLig][$i] = $plateau[$laLig][$i-1];
							$plateau[$laLig][$i-1] = null;
							if ($gg && $plateau[$laLig][$i] != null && $plateau[$laLig][$i]['type'] != "rocher" && $plateau[$laLig][$i]['sens'] == "droite") {
								$unGagnant = $plateau[$laLig][$i]['type'];
								$gg = false;
							}
						}
						else {
							$plateau[$laLig][$i] = null;
						}
					}
					if ($lig == -2) {
						$plateau[$laLig][0] = $spawn_e[$col];
						$spawn_e[$col] = null;
					}
					else if ($lig == -3) {
						$plateau[$laLig][0] = $spawn_r[$col];
						$spawn_r[$col] = null;
					}
					return true;
				}
				else {
					return false;
				}
			}
		}
	}
	function estDuBonSens($lig,$col,$plateau,$spawn_e,$spawn_r,$sens) {
		if ($lig == -2) {
			return $spawn_e[$col]['sens'] == $sens;
		} else if ($lig == -3) {
			return $spawn_r[$col]['sens'] == $sens;
		} else {
			return $plateau[$lig][$col]['sens'] == $sens;
		}
	}
	function recupCase($lig,$col,$plateau,$spawn_e,$spawn_r) {
		//On recupére la case de départ
		//Si c'est un pion sur le plateau
		if ($lig != -2 && $lig != -3) {
			$n = $plateau[$lig][$col];
		} else { //Sinon si c'est un pion du spawn
			if ($lig == -2) {
				$n = $spawn_e[$col];
			}
			else {
				$n = $spawn_r[$col];
			}
		}
		return $n;
	}
	function resetCase($lig,$col,&$plateau,&$spawn_e,&$spawn_r) {
		if ($lig == -2) {
			$spawn_e[$col] = null;
		}
		else if ($lig == -3) {
			$spawn_r[$col] = null;
		}
		else {
			$plateau[$lig][$col] = null;
		}
	}
	function changerSens($lig,$col,&$plateau,&$spawn_e,&$spawn_r,$sens) {
		$n = recupCase($lig,$col,$plateau,$spawn_e,$spawn_r);
		if ($n['sens'] != $sens) {
			if($lig == -2) {
				$spawn_e[$col]['sens'] = $sens;
			} else if ($lig == -3) {
				$spawn_r[$col]['sens'] = $sens;
			}
			else {
				$plateau[$lig][$col]['sens'] = $sens;
			}
		}
	}
	function appelFonction($lig,$col,$plateau,$equipe) {
		if ($plateau[$lig][$col] == null) {
			echo "'cliqueCase(".$lig.",".$col.",\"".$equipe."\",".null.");'";
		} else {
			echo "'cliqueCase(".$lig.",".$col.",\"".$equipe."\",".json_encode($plateau[$lig][$col]).");'";
		}
	}
	function appelFonctionSpawn($spawn,$id,$s,$equipe) {
		if ($spawn[$id] == null) {
			echo "'cliqueCaseSpawn2(\"".$s."\",\"".$equipe."\");'";
		} else {
			echo "'cliqueCaseSpawn(".json_encode($spawn[$id]).",\"".$s."\",\"".$equipe."\");'";
		}
	}
	function ancienSens($lig,$col,$plateau,$spawn_e,$spawn_r) {
		if ($lig == -2) {
			return $spawn_e[$col]['sens'];
		} else if ($lig == -3) {
			return $spawn_r[$col]['sens'];
		} else {
			return $plateau[$lig][$col]['sens'];
		}
	}
	function majCoup(&$plateau,&$spawn_e,&$spawn_r,$lig_dep,$col_dep,$lig_arr,$col_arr,$sens,&$unGagnant) {
		$ancien_sens = ancienSens($lig_dep,$col_dep,$plateau,$spawn_e,$spawn_r);
		changerSens($lig_dep,$col_dep,$plateau,$spawn_e,$spawn_r,$sens);
		//Si il y a un déplacement
		if ($lig_arr != -1 && $col_arr != -1) {
			if (!deplacer($lig_dep,$col_dep,$lig_arr,$col_arr,$plateau,$spawn_e,$spawn_r,$sens,$unGagnant)) {
				changerSens($lig_dep,$col_dep,$plateau,$spawn_e,$spawn_r,$ancien_sens);
				return false;
			}
		}
		return true;
	}
	function test(){
		return "e1";
	}
?>