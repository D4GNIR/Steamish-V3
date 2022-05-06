<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
                'label' =>'Nom',
                'attr' => [
                'placeholder' => 'Nom',
                ]])
            ->add('email',TextType::class, [
                'label' =>'E-mail',
                'attr' => [
                'placeholder' => 'E-mail',
                ]])
            ->add('nickname',TextType::class, [
                'label' =>'Pseudo',
                'attr' => [
                'placeholder' => 'Pseudo',
                ]])
            ->add('slug',TextType::class, [
                'label' =>'Slug',
                'attr' => [
                'placeholder' => 'Slug',
                ]])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
                'attr' => [
                    'class' => 'btn btn-steamish'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
