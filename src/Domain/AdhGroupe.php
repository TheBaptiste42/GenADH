<?php
	
namespace GenADH\Domain;

class AdhGroupe {

	/**
	* User id
	*
	* @var integer
	*/
	private $id;
	
	/**
	* Libelle name
	*
	* @var string
	*/
	private $libelle;
	
	/**
	* User Type de cotisation
	*
	* @var string
	*/
	private $typecotisation;
	
	public function getId() { return $this->id; }
	public function getLibelle() { return $this->libelle; }
	public function getTypeCotisation() { return $this->typecotisation; }

	public function setId($id) { $this->id = $id; }
	public function setLibelle($libelle) { $this->libelle = $libelle; }
	public function setTypeCotisation($typecotisation) { $this->typecotisation = $typecotisation; }
}