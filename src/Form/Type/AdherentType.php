<?php

namespace GenADH\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdherentType extends AbstractType
{
	private $grplist;

	public function __construct($grp){
		$groupeList = array();
		foreach ($grp as $val) {
			$groupeId = $val->getId();
			$groupeList[$groupeId] = $val->getLibelle();
		}
		$this->grplist = $groupeList;
	}
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
			$builder->add('civilite', 'choice', array('choices' => array('M' => 'M', 'Mme' => 'Mme', 'Mlle' => 'Mlle')));			
			$builder->add('nom', 'text');
			$builder->add('prenom', 'text');
			$builder->add('adresse', 'text', array('required' => false));
			$builder->add('codepostal', 'text', array('required' => false));
			$builder->add('ville', 'text', array('required' => false));
			$builder->add('departement', 'text', array('required' => false));
			$builder->add('pays', 'text', array('required' => false));
			
			$builder->add('groupe', 'choice', array('choices' => $this->grplist));
			
			$builder->add('email', 'text', array('required' => false));
			$builder->add('phone', 'text', array('required' => false));
			$builder->add('mobile', 'text', array('required' => false));
	}
	public function getName()
	{
			return 'adherent';
	}
}