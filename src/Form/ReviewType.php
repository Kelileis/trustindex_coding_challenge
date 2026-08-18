<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company_name', TextType::class, [
                'attr' => ['placeholder' => 'Cég neve'],
            ])
            ->add('rating', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                    'placeholder' => '1-5',
                ],
                'label' => 'Értékelés (1-5)',
            ])
            ->add('review_text', TextareaType::class, [
                'attr' => ['rows' => 5, 'placeholder' => 'Írd le a véleményed...'],
                'label' => 'Vélemény',
            ])
            ->add('author_email', EmailType::class, [
                'attr' => ['placeholder' => 'pelda@email.com'],
                'label' => 'E-mail cím',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
