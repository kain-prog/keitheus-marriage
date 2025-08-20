<?php

namespace App\Bundles\GuestBundle\Forms;

use App\Bundles\GuestBundle\Entity\Guest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GuestConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('guest', EntityType::class, [
                'class' => Guest::class,
                'label' => 'Nome',
                'choice_label' => 'name',
                'placeholder' => 'Procure o seu nome...',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Por favor, selecione um convidado.']),
                ],
                'attr' => [
                    'class' => 'form-control focus:outline-gray-400 marcellus'
                ]
            ])
            ->add('guest_not_come', TextType::class, [
                'label' => 'Caso algum desses convidados não possam comparecer, digite o nome aqui:',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'id' => 'guest_not_come',
                    'placeholder' => 'Digite o nome de quem não poderá comparecer com você.',
                    'class' => 'px-2 w-full border border-gray-300 rounded-md p-1 mt-1 focus:outline-gray-400 marcellus',
                ],
            ])
//            ->add('companions_number', IntegerType::class, [
//                'label' => 'Número de Acompanhantes:',
//                'mapped' => false,
//                'required' => true,
//                'attr' => [
//                    'placeholder' => '0, 1, 2...',
//                    'class' => 'w-full border border-gray-300 rounded-md p-1 mt-1 focus:outline-gray-400 marcellus',
//                ],
//            ])
//            ->add('companions_list', HiddenType::class, [
//                'mapped' => false,
//                'required' => false,
//                'attr' => [
//                    'data-companions-target' => 'hidden',
//                    'id' => 'guest_confirmation_companions_list',
//                ],
//            ])
            ->add('is_confirmed', ChoiceType::class, [
                'label' => 'Confirma presença?',
                'choices' => [
                    'Sim' => true,
                    'Não' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'choice_attr' => function ($choice, $key, $value) {
                    return [
                        'class' => 'peer hidden marcellus',
                    ];
                },
                'choice_value' => function (?bool $choice) {
                    return $choice === null ? '' : ($choice ? '1' : '0');
                },
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Mensagem: (opcional)',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Deixe uma mensagem carinhosa para os noivos...',
                    'rows' => 4,
                    'class' => 'w-full border border-gray-300 rounded-md p-2 focus:outline-gray-400 marcellus',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guest::class,
        ]);
    }
}
