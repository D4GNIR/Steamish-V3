<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Comment;
use App\Entity\Game;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content',TextareaType::class, [
                'label' =>'Commentaire',
                'attr' => [
                    'placeholder' => 'Je pense que ce jeu...',
                ]])
            // ->add('upVotes')
            // ->add('downVotes')
            // ->add('createdAt')
            ->add('note', HiddenType::class, [
                'attr' => [
                    'placeholder' => '2...',
                ]])
            // ->add('account', EntityType::class, [
            //     'class' => Account::class,
            //     'choice_label' => 'name'
            // ])
            // ->add('game', EntityType::class, [
            //     'class' => Game::class,
            //     'choice_label' => 'name'
            // ])
        //     ->add('submit', SubmitType::class, [
        //         'label' => 'Soumettre',
        //         'attr' => [
        //             'class' => 'btn btn-steamish'
        //     ]
        // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}