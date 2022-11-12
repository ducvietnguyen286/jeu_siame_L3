<?php
define ("ELEPHANT","elephant");
define ("RHINO","rhino");
define ("ROCHER","rocher");
define ("HAUT","haut");
define ("DROITE","droite");
define ("BAS","bas");
define ("GAUCHE","gauche");
class pion {
	private $type;
	private $sens;
	private $id;

	public function __construct($id,$type,$sens) {
		$this->id = $id;
		$this->type = $type;
		$this->sens = $sens;
	}

	public function afficher() {
		echo ">> ".$this->id." --> ".$this->type." & ".$this->sens;
	}

	public function tourner() {
		if ($this->sens == HAUT) {
			$this->sens = DROITE;
		}
		else if ($this->sens == DROITE) {
			$this->sens = BAS;
		}
		else if ($this->sens == BAS) {
			$this->sens = GAUCHE;
		}
		else if ($this->sens == GAUCHE) {
			$this->sens = HAUT;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getType() {
		return $this->type;
	}

	public function getSens() {
		return $this->sens;
	}

	public function getImg() {
		if ($this->type == ELEPHANT) {
			$ch="e";
		}
		else if ($this->type == RHINO) {
			$ch="r";
		} else if ($this->type == ROCHER) {
			$ch="c";
		}
		$ch = $ch . $this->id;
		return "./images/pions/".$ch.".png";
	}

	public function getArray() {
		return array("id"=>$this->id,"type"=>$this->type,"sens"=>$this->sens);
	}
}
?>