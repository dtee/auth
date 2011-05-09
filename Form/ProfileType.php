<?php
namespace Odl\AuthBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class ProfileType
	extends AbstractType
{
	public function __construct() {
	}

	public function buildForm(FormBuilder $builder, array $options)
    {
    	$builder
    		->add('firstName', 'text', array(
    			'required' => true
    		))
    		->add('lastName', 'text', array(
    			'required' => true
    		));
    }


	public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Odl\AuthBundle\Documents\Profile',
        );
    }
}