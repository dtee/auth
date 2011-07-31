<?php
namespace Odl\AuthBundle\Form\Handler;

use FOS\UserBundle\Model\UserManager;

use Odl\AssetBundle\Form\FormHandler;

class LoginHandler
    extends FormHandler
{
    protected $userAuth;
    protected $userManager;

    public function __construct(UserManager $userManager) {
        $this->userManager = $userManager;
        $this->userAuth = $userManager->createUser();
    }

    public function handleSuccess() {

    }

    public function getForm()
    {
        if (!$this->form)
        {
            $this->form = $formFactory->createBuilder('form', $this->userAuth, array(
                    'validation_groups' => array(
                            'Login'
                    )
            ))
                ->add('email', 'text', array(
                    'label' => 'Email',
                    'attr' => array(
                            'placeholder' => 'Email',
                            'class' => 'login-input auto-hint'
                    )
            ))
                ->add('plainPassword', 'password', array(
                    'label' => 'Password',
                    'attr' => array(
                            'placeholder' => 'Password',
                            'class' => 'login-input auto-hint'
                    )
            ))
                ->add('remember_me', 'checkbox', array(
                    'label' => 'Remember me',
                    'required' => false,
                    'attr' => array()
            ))
                ->getForm();
        }

        return $this->form;
    }

}