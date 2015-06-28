<?php

namespace GenADH\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdhGroupeType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
			$builder->add('libelle', 'text');			
			$builder->add('typecotisation', 'choice', array('choices' => array('1' => 'Doit payer les cotisations', '0' => 'Exempt de cotisations')));
	}
	public function getName()
	{
			return 'groupe';
	}
}