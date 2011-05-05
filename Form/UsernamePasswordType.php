<?php
namespace Odl\AuthBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class UsernamePasswordType
	extends AbstractType
{
	public function __construct() {
	}

	public function buildForm(FormBuilder $builder, array $options)
    {
    	$builder
    		->add('email')
    		->add('password', 'text');
    }

	public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Odl\AuthBundle\Documents\UsernamePasswordAuth',
        	'error_bubbling' => false
        );
    }

}