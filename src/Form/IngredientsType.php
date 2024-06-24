<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Ingredients;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IngredientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                ],
                "label" => "Nom",
                'label_attr' => [
                    'class' => 'textStyle'
                ],
            ])
            ->add('image', FileType::class, [
                "mapped" => false, 
                "required" => false
            ])
            ->add('allergene', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off',
                ],
                "label" => "Allergene",

                'label_attr' => [
                    'class' => 'textStyle'
                ],
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'label_attr' => [
                    'class' => 'textStyle'
                ],
            ])
            ->add('submit', SubmitType::class, 
            ["label" => "Envoyer",
            "attr" => [
                'class' => 'button'
            ]])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredients::class,
        ]);
    }
}
