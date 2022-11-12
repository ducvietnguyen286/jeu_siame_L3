const coords = ["00","01","02","03","04","10","11","12","13","14","20","21","22","23","24","30","31","32","33","34","40","41","42","43","44"];
const coordsAutour = ["00","01","02","03","04","10","14","20","24","30","34","40","41","42","43","44"];
var leId = -1;
var leType;
var leSens;
var typeActu;
var sensActu;
var idActu = -1;
function cliqueCase(lig,col,equipe,elt) {
	if (elt != null) {
		idActu = elt['id'];
		typeActu = elt['type'];
		sensActu = elt['sens'];
	}
	else {
		idActu = -1;
		typeActu = "";
		sensActu = "";
	}
	majInput(lig,col,equipe);
}
//Met à jour les input caché afin de récupérer les coordonnées en php
function majInput(lig,col,equipe) {
	if ((idActu == -1 && leId == -1) || (leId == -1 && (idActu != -1 && typeActu == "rocher")) || (leId == -1 && (idActu != -1 && typeActu != equipe)))return;
	//Si la case de départ est déjà selectionné et si on clique sur une autre case que celle de départ
	if (majInputDep(lig,col) == true) {
		//On met a jour la case d'arrivé
		majInputArr(lig,col);
	} else {
		//On met a jour le input du sens de la carte si on choisi une carte depart
		if (leSens != "") {
			document.getElementById("rotation").value=leSens;
		}
		else {
			document.getElementById("rotation").value = -1;
		}
	}
	majSubmit();
}

//Permet de mettre à jour les inputs qui contienne les coordonnées de la case de départ
//Met également à jour la page web au niveau css avec la case de départ entouré d'une bordure bleu
function majInputDep(lig,col) {
	var v1 = document.getElementById("lig_depart").value;
	var v2 = document.getElementById("col_depart").value;
	//Si les valeurs sont égale aux ancienne de départ ==> on reinit
	if (v1 == lig && v2 == col) {
		var v3 = document.getElementById("lig_arrive").value;
		var v4 = document.getElementById("col_arrive").value;
		//On reinit la rotation de la carte de départ
		reinitCarte();
		//Si la case d'arrivé est définit
		if (v3 != -1 && v4 != -1) {
			//Alors on enlève la bordure de case d'arrivé de l'ancienne
			if (v3 == -2 || v3 == -3) {
				enleveBordure(document.getElementById(leType[0]+leId.toString()),"arrive");
			}
			else {
				bordureCaseArrive(enleveBordure,parseInt(v3),parseInt(v4));
			}
		}
		//On réinitialise les coordonnées des cases départ/arrive
		document.getElementById("lig_depart").value="-1";
		document.getElementById("col_depart").value="-1";
		document.getElementById("lig_arrive").value="-1";
		document.getElementById("col_arrive").value="-1";
		//Maj bordure
		if (lig != -2 && lig != -3) {
			BordureCaseACote(enleveBordure,lig,col);
			enleveBordure(document.getElementById(leType[0]+leId.toString()),"bordure");
		} else {
			bordureAutour(enleveBordure);
			if (lig == -2) {
				enleveBordure(document.getElementById("e"+col),"depart");
			} 
			else if (lig == -3) {
				enleveBordure(document.getElementById("r"+col),"depart");
			}
		}
		leType="";
		leId=-1;
		leSens="";
		return false;
	} //Si la coordonnée est différente 
	else if (v1 != lig || v2 != col) {
		//Si on n'a pas encore choisi une case de départ
		if (v1 == -1 && v2 == -1) {
			document.getElementById("lig_depart").value=lig;
			document.getElementById("col_depart").value=col;
			leId = idActu;
			leSens = sensActu;
			leType = typeActu;
			//On ajoute des bordure aux case qu'on pourra selectionner
			//Si on a cliqué sur une case du plateau
			if (lig != -2 && lig != -3) {
				BordureCaseACote(ajouteBordure,lig,col);
				//Si la case de départ est sur une extremité
				if (coordsAutour.indexOf(lig.toString()+col.toString()) != -1) {
					ajouteBordure(document.getElementById(typeActu[0]+idActu.toString()),"bordure")
				}
			} else {
				bordureAutour(ajouteBordure);
				if (lig == -2) {
					ajouteBordure(document.getElementById("e"+col),"depart");
				} 
				else if (lig == -3) {
					ajouteBordure(document.getElementById("r"+col),"depart");
				}
			}
			return false;
		}
		return true;
	}
}

//Permet de mettre à jour les inputs des coordonnées d'arrivé
// Ainsi que le cs
function majInputArr(lig,col) {
	var v1 = document.getElementById("lig_depart").value;
	var v2 = document.getElementById("col_depart").value;
	//Si les coords de départ sont initialisé
	if (v1 != -1 && v2 != -1) {
		var v3 = document.getElementById("lig_arrive").value;
		var v4 = document.getElementById("col_arrive").value;
		//Si on veut selectionner une case arrivé au spawn
		if ((lig == -2 || lig == -3)) {
			//Si la case de départ est déja au spawn
			if (v1 == -2 || v1 == -3) {
				return;
			}
			if (col != leId) {
				return;
			}
			if (coordsAutour.indexOf(v1.toString()+v2.toString()) == -1) {
				return;
			}
			//Si c'est l'ancienne case d'arrivé on la supprime
			if (document.getElementById("lig_arrive").value == lig && document.getElementById("col_arrive").value == col) {
				document.getElementById("lig_arrive").value = "-1";
				document.getElementById("col_arrive").value = "-1";
				enleveBordure(document.getElementById(leType[0]+leId),"arrive");
			} else {
				console.log('Pioou');
				document.getElementById("lig_arrive").value = lig;
				document.getElementById("col_arrive").value = col;
				ajouteBordure(document.getElementById(leType[0]+leId),"arrive");
				if (v3 != -2 && v3 != -3 && v3 != -1) {
					bordureCaseArrive(enleveBordure,parseInt(v3),parseInt(v4));
				} 
			}
			return true;
		}
		//Si c'est une case qui se situe a coté
		if ((v1 == lig && ((v2 == col+1) || (v2 == col-1))) || ((v2 == col) && ((v1 == lig+1) || (v1 == lig-1)))) {
			//Si c'est l'ancienne case d'arrivé on la supprime
			if (document.getElementById("lig_arrive").value == lig && document.getElementById("col_arrive").value == col) {
				document.getElementById("lig_arrive").value = "-1";
				document.getElementById("col_arrive").value = "-1";
				bordureCaseArrive(enleveBordure,lig,col);
			} else {
				if (v3 != -1 && v4 != -1) {
					if (v3 == -2 || v3 == -3) {
						enleveBordure(document.getElementById(leType[0]+leId),"arrive");
					} else {
						bordureCaseArrive(enleveBordure,parseInt(v3),parseInt(v4));
					}
				}
				document.getElementById("lig_arrive").value = lig;
				document.getElementById("col_arrive").value = col;
				bordureCaseArrive(ajouteBordure,lig,col);
			}
			return true;
		}
		else if (v1 == -2 || v1 == -3) {
			if (coordsAutour.indexOf(lig.toString()+col.toString()) != -1) {
				//Si c'est l'ancienne case d'arrivé on la supprime
				if (document.getElementById("lig_arrive").value == lig && document.getElementById("col_arrive").value == col) {
					document.getElementById("lig_arrive").value = "-1";
					document.getElementById("col_arrive").value = "-1";
					bordureCaseArrive(enleveBordure,lig,col);
				} else {
					if (v3 != -1 && v4 != -1) {
						bordureCaseArrive(enleveBordure,parseInt(v3),parseInt(v4));
					}
					document.getElementById("lig_arrive").value = lig;
					document.getElementById("col_arrive").value = col;
					bordureCaseArrive(ajouteBordure,lig,col);
				}
				return true;
				}
			else {
			}
		}
	}
}

//Permet d'ajouter/enlever les bordures aux cases à cotés de celle de départ
function BordureCaseACote(f,lig,col) {
	if (lig > 0) {
		f(document.getElementById(coords[5*(lig-1)+col]),"bordure");
	}
	if (lig < 4) {
		f(document.getElementById(coords[5*(lig+1)+col]),"bordure");
	}
	if (col > 0) {
		f(document.getElementById(coords[5*lig+col-1]),"bordure");
	}
	if (col < 4) {
		f(document.getElementById(coords[5*lig+col+1]),"bordure");
	}
	f(document.getElementById(coords[5*lig+col]),"depart");
}

//Ajoute/enleve la bordure sur la case d'arrive
function bordureCaseArrive(f,lig,col) {
	f(document.getElementById(coords[5*lig+col]),"arrive");
}

function bordureAutour(f) {
	coordsAutour.forEach((c)=>f(document.getElementById(c),"bordure"));
}

//ajoute la bordure type à e
function ajouteBordure(e,type) {
	if (type == "arrive") {
		enleveBordure(e,"bordure");
	}
	e.classList.add(type);
}

//enleve la bordure type à e
function enleveBordure(e,type) {
	if (type == "arrive") {
		ajouteBordure(e,"bordure");
	}
	e.classList.remove(type);
}

function cliqueCaseSpawn(elt,spawn,equipe) {
	if(elt != null) {
		idActu = elt['id'];
		typeActu = elt['type'];
		sensActu = elt['sens'];
		if (typeActu == "elephant") {
			majInput("-2",elt['id'],equipe);
		}
		else if (typeActu == "rhino") {
			majInput("-3",elt['id'],equipe);
		}
	}
	else {
		idActu = -1;
		typeActu = "";
		sensActu = "";
		if (leType == null) return;
		if (leType[0] == spawn) {
			if (spawn == "r") {
				majInput("-3",leId,equipe);
			} else if (leType[0] == "e") {
				majInput("-2",leId,equipe);
			}
		}
	}
}
function cliqueCaseSpawn2(spawn,equipe) {
	cliqueCaseSpawn(null,spawn,equipe);
}
function tournerCarte() {
	if (leSens != "") {
		var v1 = document.getElementById("lig_depart").value;
		var v2 = document.getElementById("col_depart").value;
		var v3 = document.getElementById("rotation").value;
		if (v3 == "haut") {
			document.getElementById("rotation").value="droite";
		} else if (v3 == "droite") {
			document.getElementById("rotation").value="bas";
		} else if (v3 == "bas") {
			document.getElementById("rotation").value="gauche";
		} else if (v3 == "gauche") {
			document.getElementById("rotation").value="haut";
		}
		//console.log("v3 : "+document.getElementById("rotation").value+" & leSens : "+leSens);
		if (v1 != -2 && v1 != -3) {
			document.getElementById(v1.toString()+v2.toString()).classList.remove("rota_"+v3);
			document.getElementById(v1.toString()+v2.toString()).classList.add("rota_"+document.getElementById("rotation").value);
		} else {
			document.getElementById(leType[0]+leId).classList.remove("rota_"+v3);
			document.getElementById(leType[0]+leId).classList.add("rota_"+document.getElementById("rotation").value);
		}
		majSubmit();
	}
}
function reinitCarte() {
	var v1 = document.getElementById("lig_depart").value;
	var v2 = document.getElementById("col_depart").value;
	var v3 = document.getElementById("rotation").value;
	if (v1 != -2 && v1 != -3) {
		document.getElementById(v1.toString()+v2.toString()).classList.remove("rota_"+v3);
		document.getElementById(v1.toString()+v2.toString()).classList.add("rota_"+leSens);
	}else {
		document.getElementById(leType[0]+leId).classList.remove("rota_"+v3);
		document.getElementById(leType[0]+leId).classList.add("rota_"+leSens);
	}
	document.getElementById("rotation").value=-1;
}
function majSubmit() {
	var v1 = document.getElementById("lig_depart").value;
	var v2 = document.getElementById("col_depart").value;
	var v3 = document.getElementById("lig_arrive").value;
	var v4 = document.getElementById("col_arrive").value;
	var v5 = document.getElementById("rotation").value;

	if (v1 != -1 && v2 != -1 && v5 != leSens ) {
		document.getElementById("btn_valider").disabled=false;
	}
	else if (v1 != -1 && v2 != -1 && v3 != -1 && v4 != -1) {
		document.getElementById("btn_valider").disabled=false;
	}
	else {
		document.getElementById("btn_valider").disabled=true;
	}

}