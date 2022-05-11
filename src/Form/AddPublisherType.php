<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPublisherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' =>'Nom',
                'attr' => [
                    'placeholder' => 'Nom',
                ]])
            ->add('website', TextType::class, [
                'label' =>'Site Web',
                'attr' => [
                    'placeholder' => 'Site Web',
                ]])
            // ->add('slug')
            ->add('createdAt', DateType::class, [
                'label' =>'Date de création',
                'widget' => 'single_text',
                ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
                'attr' => [
                    'class' => 'btn btn-steamish'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publisher::class,
        ]);
    }
}
