<?php
namespace Odl\AuthBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class ProfileType
    extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('firstName', 'text', array(
                'required' => true,
                'label' => 'First name',
                'attr' => array(
                        'placeholder' => 'First name',
                )
        ))
            ->add('lastName', 'text', array(
                'required' => true,
                'label' => 'Last name',
                'attr' => array(
                        'placeholder' => 'Last name',
                )
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
                'data_class' => 'Odl\AuthBundle\Documents\Profile',
        );
    }

    public function getName() {
        return 'user_auth_profile';
    }
}