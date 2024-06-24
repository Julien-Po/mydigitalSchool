<?php

namespace App\Form;

use App\Entity\Calendar;
use App\Validator\NoMonday;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $twoWeeksLater = new \DateTimeImmutable('+14 days');

        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom de la réservation', 
                'attr' => [
                    'autocomplete' => 'off',
                ],
            ])
            ->add('start', DateTimeType::class, [
                'date_widget' => 'single_text',
                'label' => 'Date et heure de la réservation',
                'hours' => range(12, 22), // Limite de 9h à 17h,
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => $twoWeeksLater,
                        'message' => 'La date de début doit être au moins deux semaines à partir d\'aujourd\'hui.'
                    ]),
                    new NoMonday()
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Précisions ( Nombre de personne, régime spécifique )'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
