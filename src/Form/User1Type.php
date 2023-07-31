<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class User1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('cin')
            ->add('nom')
            ->add('prenom')
            ->add('numTel')
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'h-full-width',
                    "placeholder" => "Emain de confirmation vous sera envoyer"
                ],
                'label' => "Email"
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    "class" => "h-full-width"
                ],
                'label' => "Mot de passe"
            ])
            
            
           /*  ->add('roles', ChoiceType::class, array(
                'attr'  =>  array('class' => 'form-control',
                'style' => 'margin:5px 0;'),
                'choices' => 
                array
                (
                    'ADMIN' => array
                    (
                        'Yes' => 'ADMIN',
                    ),
                    'GRH' => array
                    (
                        'Yes' => 'GRH'
                    ),
                    'RESP_FiNANCE' => array
                    (
                        'Yes' => 'RESP_FINANCE'
                    ),
                    'EMPLOYE' => array
                    (
                        'Yes' => 'EMPLOYE'
                    ),
                ) 
                ,
                'multiple' => false,
                'required' =>true)) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
