<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('cin')
            ->add('nom')
            ->add('prenom')
            ->add('numTel')
            ->add('createdAt')
            ->add('enabled')
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
