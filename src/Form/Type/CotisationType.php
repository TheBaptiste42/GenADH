<?php

namespace GenADH\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CotisationType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
			$builder->add('montant', 'text');
	}
	public function getName()
	{
			return 'adherent';
	}
}