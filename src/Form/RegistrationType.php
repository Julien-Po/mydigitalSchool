<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;




class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class
            , [])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe'
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ])
            ->add('fullname', TextType::class, [
                'attr' => [
                    'class' => 'registrationFullName'
                ],
                'label' => 'Nom/Prénom'
            ])
            ->add ('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
