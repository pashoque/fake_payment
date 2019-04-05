<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', MoneyType::class, [
                'divisor' => 100,
                'currency' => null,
                'constraints' => [
                    new GreaterThan(0),
                    new NotBlank()
                ],
            ])
            ->add('currency', CurrencyType::class, [
                'choices' => [
                    'Грн' => 'UAH',
                    'Евро' => 'EURO' ,
                    'Доллар' => 'USD'
                ],
                'choice_loader' => null,
            ])
            ->add('callbackUrl', UrlType::class, [
                'constraints' => [
                    new NotBlank()
                ],
            ])
            ->add('save', SubmitType::class);
    }
}