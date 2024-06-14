<?php

// src/Form/PaymentType.php

namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cardNumber', NumberType::class, [
                'label' => 'Numéro de carte'
            ])
            ->add('cardHolderName', NumberType::class, [
                'label' => 'Nom du possesseur de la carte'
            ])
            ->add('expiryDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'expiration'
            ])
            ->add('cvv', IntegerType::class, [
                'label' => 'CVV',
                
            ])
            ->add('amount', MoneyType::class, [
                'currency' => 'euros',
                'label' => 'Montant',
                'data' => '50',
                
            ])
            ->add('submit', SubmitType::class, 
            ["label" => "Paiement", 
                "attr" => [
                    "class" => "button"
                ]
            ])
            ;
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
