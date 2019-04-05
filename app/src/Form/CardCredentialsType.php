<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CardCredentialsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cardNumber', TextType::class, [
                'constraints' => [
                  new Length(['max' => 16, 'min' => 16])
                ],

            ])
            ->add('cvv', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 3])
                ],
            ])
            ->add('holder', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ],
            ])
            ->add('save', SubmitType::class);
    }
}