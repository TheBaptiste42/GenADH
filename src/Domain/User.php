<?php
	
namespace GenADH\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface {

	/**
	* User id
	*
	* @var integer
	*/
	private $id;
	
	/**
	* User name
	*
	* @var string
	*/
	private $username;
	
	/**
	* User password
	*
	* @var string
	*/
	private $password;
	
	/**
	* Salt that was originally used to encode the password.
	*
	* @var string
	*/
	private $salt;
	
	/**
	* Role
	* Values : ROLE_USER or ROLE_ADMIN
	*
	* @var string
	*/
	private $role;
	
	/**
	* Prenom
	*
	* @var string
	*/
	private $prenom;
	
	/**
	* Nom
	*
	* @var string
	*/
	private $nom;
	
	/**
	* Est un adhérent
	* Values : 0->false, 1->true
	*
	* @var bool
	*/	
	private $isadh;
	
	/**
	* Id de l'adhérent
	*
	* @var integer
	*/
	private $idadh;
	
	public function getId() { return $this->id; }
	public function getUsername() { return $this->username; }
	public function getPassword() { return $this->password; }
	public function getSalt() { return $this->salt; }
	public function getRole() { return $this->role; }
	public function getPrenom() { return $this->prenom; }
	public function getNom() { return $this->nom; }
	public function getIsAdh() { return $this->isadh; }
	public function getIdAdh() { return $this->idadh; }
	
	public function setId($id) { $this->id = $id; }
	public function setUsername($username) { $this->username = $username; }
	public function setPassword($password) { $this->password = $password; }
	public function setSalt($salt) { $this->salt = $salt; }
	public function setRole($role) { $this->role = $role; }
	public function setNom($nom) { $this->nom = strtoupper($nom); }
	public function setPrenom($prenom) { $this->prenom = ucfirst($prenom); }
	public function setIsadh($isadh) { $this->isadh = $isadh; }
	public function setIdadh($idadh) { $this->idadh = $idadh; }
	
	
	/**
	* InheritDoc
	*/
	public function getRoles() { return array($this->getRole()); }
	/**
	* InheritDoc
	*/
	public function eraseCredentials() {}
}