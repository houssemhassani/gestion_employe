<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => 'Start Date',
            ])
            ->add('finalDate', DateType::class, [
                'label' => 'Final Date',
            ])
            ->add('adress', TextType::class, [
                'label' => 'Address',
            ])
            ->add('employes', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'Nom', // You need to define this method in the User entity
                'multiple' => true,
                'expanded' => true,
                'label' => 'Employees',
            ])
        ->add('submit', SubmitType::class, [
        'label' => 'Create Formation',
        'attr' => ['class' => 'btn btn-primary'],
    ])
        ->add('reset', ResetType::class, [
            'label' => 'Reset',
            'attr' => ['class' => 'btn btn-secondary'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}