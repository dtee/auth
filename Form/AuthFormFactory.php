<?php
namespace Odl\AuthBundle\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;
use Odl\AuthBundle\Documents\UserAuth;

class AuthFormFactory
{
    /**
     *
     * @var Request
     */
    protected $formFactory;

    /**
     *
     * @var Request
     */
    protected $request;

    public function __construct(Request $request, FormFactory $formFactory)
    {

        $this->request = $request;
        $this->formFactory = $formFactory;
    }

    public function getRegisterForm(UserAuth $userAuth)
    {
        $facebook = $this->get('facebook');
        $formFactory = $this->get('form.factory');
        $request = $this->get('request');

        $form = $formFactory->createBuilder('form', $userAuth, array(
                'label' => 'Sign up'
        ))
            ->add('profile', new ProfileType(), array(
                'label' => 'Profile Information'
        ))
            ->add('usernamePasswordAuth', new UsernamePasswordType(), array(
                'label' => 'Account Information'
        ))
            ->getForm();

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);

            if ($form->isValid())
            {
                // Call back function
            }
        }

        return $form;
    }

    public function getLoginForm(UserAuth $userAuth)
    {

    }
}