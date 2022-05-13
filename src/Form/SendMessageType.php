<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\DirectMessage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content',TextareaType::class, [
                'attr' => [
                    'placeholder' => '....',
                ]])
            ->add('receiver',EntityType::class, [
                'class' => Account::class,
                'choice_label' => 'email',                  
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoi',
                'attr' => [
                    'class' => 'btn btn-steamish'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DirectMessage::class,
        ]);
    }
}
