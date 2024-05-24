<?php

namespace App\Form;

use App\Entity\Ingredients;
use App\Entity\Recipes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredients', EntityType::class, [
                'class' => Ingredients::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])

            ->add('submit', SubmitType::class, 
            ["label" => "Envoyer"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipes::class,
        ]);
    }
}
