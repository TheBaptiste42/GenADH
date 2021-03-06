<?php

namespace GenADH\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
	private $adhlist;
	private $usernow;

	public function __construct($adh, $usernow){
		$adherentList = array();
		foreach ($adh as $val) {
			$adherentId = $val->getId();
			$adherentList[$adherentId] = $val->getNom() . " " . $val->getPrenom();
		}
		$this->adhlist = $adherentList;
		$this->usernow = $usernow;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
			$builder->add('idadh', 'choice', array('choices' => $this->adhlist));			
			$builder->add('username', 'text');
			$builder->add('password', 'password', array('required' => false));
			if (!$this->usernow) { $builder->add('role', 'choice', array('choices' => array('ROLE_USER' => 'Utilisateur', 'ROLE_ADMIN' => 'Administrateur'))); }
			$builder->add('nom', 'text');
			$builder->add('prenom', 'text');
			$builder->add('isadh', 'choice', array('choices' => array('1' => 'L\'utilisateur est adhérent', '0' => 'L\'utilisateur n\'est pas adhérent')));
	}
	public function getName()
	{
			return 'adherent';
	}
}