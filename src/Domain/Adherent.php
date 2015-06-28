<?php

namespace GenADH\Domain;

class Adherent
{
	/**
     	* Adherent id.
	*
	* @var integer
	*/	
	private $id;
	/**
     	* Adherent civilite.
	*
	* @var string
	*/
	private $civilite;
	/**
     	* Adherent nom.
	*
	* @var string
	*/
	private $nom;
	/**
     	* Adherent prenom.
	*
	* @var string
	*/
	private $prenom;
	/**
     	* Adherent groupe.
	*
	* @var integer
	*/
	private $groupe;
	/**
     	* Adherent adresse.
	*
	* @var string
	*/
	private $adresse;
	/**
     	* Adherent code postal.
	*
	* @var integer
	*/
	private $codepostal;
	/**
     	* Adherent ville.
	*
	* @var string
	*/
	private $ville;
	/**
     	* Adherent departement.
	*
	* @var string
	*/
	private $departement;
	/**
		* Pays de l'adhérent
	*
	* @var string
	*/
	private $pays;
	/**
     	* Adherent email.
	*
	* @var string
	*/
	private $email;
	/**
     	* Adherent phone.
	*
	* @var string
	*/
	private $phone;
	/**
     	* Adherent mobile.
	*
	* @var string
	*/
	private $mobile;
	/**
     	* Adherent Date de naissance.
	*
	* @var date
	*/
	private $datenaiss;
	/**
     	* Adherent Date de la creation
	*
	* @var datetime
	*/
	private $datecreation;
	/**
     	* Adherent Id de User de la creation
	*
	* @var integer
	*/
	private $user_auteur;
	/**
     	* Adherent Date de la derniere modification.
	*
	* @var datetime
	*/
	private $datelastmod;
	/**
     	* Adherent Id de user de la derniere modification.
	*
	* @var integer
	*/
	private $user_lastmodauteur;
	/**
		* Adhérent a jour de ses cotisations
	*
	* @var boolean
	*/
	private $isajour;


	public function getId() { return $this->id; }
	public function getCivilite() { return $this->civilite; }
	public function getNom() { return $this->nom; }
	public function getPrenom() { return $this->prenom; }
	public function getGroupe() { return $this->groupe; }
	public function getAdresse() { return $this->adresse; }
	public function getCodePostal() { return $this->codepostal; }
	public function getVille() { return $this->ville; }
	public function getDepartement() { return $this->departement; }
	public function getPays() { return $this->pays; }
	public function getEmail() { return $this->email; }
	public function getPhone() { return $this->phone; }
	public function getMobile() { return $this->mobile; }
	public function getDateNaiss() { return $this->datenaiss; }
	public function getDateCreation() { return $this->datecreation; }
	public function getUserAuteur() { return $this->user_auteur; } 
	public function getDateLastMod() { return $this->datelastmod; }
	public function getUserLastModAuteur() { return $this->user_lastmodauteur; }
	public function getIsajour() { return $this->isajour; }

	public function setId($id) { $this->id = $id; }
	public function setCivilite($civlite) { $this->civilite = $civlite; }
	public function setNom($nom) { $this->nom = strtoupper($nom); }
	public function setPrenom($prenom) { $this->prenom = ucfirst($prenom); }
	public function setGroupe($groupe) { $this->groupe = $groupe; }
	public function setAdresse($adresse) { $this->adresse = $adresse; }
    	public function setCodePostal($codepostal) {
    		$this->codepostal = $codepostal;
   	}
    	public function setVille($ville) {
    		$this->ville = ucfirst($ville);
   	}
    	public function setDepartement($departement) {
    		$this->departement = ucfirst($departement);
   	}
	public function setPays($pays) { $this->pays = ucfirst($pays); }
    	public function setEmail($email) {
    		$this->email = $email;
   	}
    	public function setPhone($phone) {
    		$this->phone = $phone;
   	}
    	public function setMobile($mobile) {
    		$this->mobile = $mobile;
   	}
    	public function setDateNaiss($datenaiss) {
    		$this->datenaiss = $datenaiss;
   	}
    	public function setDateCreation($datecreation) {
    		$this->datecreation = $datecreation;
   	}
    	public function setUserAuteur($user_auteur) {
    		$this->user_auteur = $user_auteur;
   	}
	public function setDateLastMod($datelastmod) {
    		$this->datelastmod = $datelastmod;
   	}
    	public function setUserLastModAuteur($user_lastmodauteur) {
    		$this->user_lastmodauteur = $user_lastmodauteur;
   	}
	public function setIsajour($isajour) { $this->isajour = $isajour; }
}
