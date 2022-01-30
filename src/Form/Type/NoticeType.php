<?php

namespace App\Form;

use Symfony\Component\Form\{AbstractType, FormBuilderInterface};
use Symfony\Component\Form\Extension\Core\Type\{IntegerType, EmailType, TextType};

class NoticeType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'example@email.com'
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Budapest'
                ]
            ])
            ->add('temp_limit', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '12'
                ]
            ]);
    }
}