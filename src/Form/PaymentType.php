<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cardNumber', NumberType::class, [
                'label' => 'Numéro de carte',
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^(?:4[0-9]{12}(?:[0-9]{3})?|[25][1-7][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/',
                        'message' => 'Le numéro de carte doit contenir entre 13 et 19 chiffres.'
                    ])
                ]
            ])
            ->add('cardHolderName', TextType::class, [
                'label' => 'Nom du possesseur de la carte',
                'label_attr' => [
                    'class' => 'textStyle'
                ],
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Le nom du possesseur de la carte ne doit contenir que des lettres et des espaces.'
                    ])
                ]
            ])
            ->add('expiryDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'expiration',
                'label_attr' => [
                    'class' => 'textStyle'
                ],
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/',
                        'message' => 'La date d\'expiration doit être au format MM/YY ou MM/YYYY.'
                    ])
                ]
            ])
            ->add('cvv', IntegerType::class, [
                'label' => 'CVV',
                'label_attr' => [
                    'class' => 'textStyle'
                ],
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\d{3,4}$/',
                        'message' => 'Le CVV doit contenir 3 ou 4 chiffres.'
                    ])
                ]
            ])
            ->add('amount', MoneyType::class, [
                'currency' => 'EUR',
                'label' => 'Montant',
                'data' => '50',
            ])
            ->add('paypalEmail', EmailType::class, [
                'label' => 'Email PayPal',
           
                'label_attr' => [
                    'class' => 'textStyle'
                ],
                'required' => false,
                'constraints' => [
                    new Assert\Email([
                        'message' => 'Veuillez entrer une adresse email valide.'
                    ])
                ]
            ])
            ->add('applePayId', TextType::class, [
                'label' => 'Apple Pay ID',
                'label_attr' => [
                    'class' => 'textStyle'
                ],
                'required' => false,
                // Ajoutez des contraintes si nécessaire
            ])
            ->add('googlePayId', TextType::class, [
                'label' => 'Google Pay ID',
                'label_attr' => [
                    'class' => 'textStyle'
                ],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Paiement',
                
                'attr' => [
                    'class' => 'button'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

        ]);
    }
}
