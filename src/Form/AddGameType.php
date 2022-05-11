<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class, [
            'label' =>'Nom',
            'attr' => [
                'placeholder' => 'Nom',
            ]])
            ->add('price',IntegerType::class, [
                'label' =>'Prix',
                'attr' => [
                    'placeholder' => 'Prix',
                ]])
            ->add('publishedAt',DateType::class, [
                'label' =>'Date de publication',
                'widget' => 'single_text',
        ])
            ->add('description',TextareaType::class, [
                'label' =>'Commentaire',
                'attr' => [
                    'placeholder' => 'In nommine patrice, espiritus santum',
                ]])
            ->add('thumbnailCover',TextType::class, [
                'label' =>'Photo de couverture',
                'attr' => [
                    'placeholder' => 'Photo de couverture',
                ]])
            ->add('thumbnailLogo',TextType::class, [
                'label' =>'Photo du logo',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Photo du logo',
                    'class' => 'col-3'
                ]])
            ->add('countries',EntityType::class, [
                    'class' => Country::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'attr' => [
                        'class' => 'col-3'
                    ]                  
                ])
            ->add('genres',EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
                
            ])
            ->add('publisher',EntityType::class, [
                'class' => Publisher::class,
                'choice_label' => 'name',
            ])
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
            'data_class' => Game::class,
        ]);
    }
}
