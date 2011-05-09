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
    		->add('email', 'repeated', array(
	            'first_name' => 'Your Email',
	            'second_name' => 'Re-enter Email',
    		))
    		->add('password', 'text', array(
    			'label' => 'New password'
    		));
    }

	public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Odl\AuthBundle\Documents\UsernamePasswordAuth',
        	'error_bubbling' => false
        );
    }
}