<?php

namespace GenADH\Domain;

class Cotisation
{
	/**
     	* Cotisation id.
	*
	* @var integer
	*/	
	private $id;
	/**
     	* Date cotisation.
	*
	* @var string
	*/
	private $date;
	/**
     	* Adherent id.
	*
	* @var string
	*/
	private $adh_id;
	/**
     	* Montant
	*
	* @var string
	*/
	private $montant;
	/**
     	* int_user_auteur.
	*
	* @var integer
	*/
	private $auteur;
	
	public function getId() { return $this->id; }
	public function getDate() { return $this->date; }
	public function getAdhId() { return $this->adh_id; }
	public function getMontant() { return $this->montant; }
	public function getAuteur() { return $this->auteur; }

	public function setId($id) { $this->id = $id; }
	public function setDate($date) { $this->date = $date; }
	public function setAdhId($adhid) { $this->adh_id = $adhid; }
	public function setMontant($montant) { $this->montant = $montant; }
	public function setAuteur($auteur) { $this->auteur = $auteur; }
}
