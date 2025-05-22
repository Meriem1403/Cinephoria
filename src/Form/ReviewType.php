<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options): void
{
$builder
->add('rating', ChoiceType::class, [
'choices' => [
'👎 Bad (1)' => 1,
'👍 Okay (2)' => 2,
'👍👍 Excellent (3)' => 3,
],
'expanded' => true,
'label' => 'Your rating'
])
->add('comment', TextareaType::class, [
'required' => false,
'label' => 'Your comment',
'attr' => ['rows' => 4]
]);
}

public function configureOptions(OptionsResolver $resolver): void
{
$resolver->setDefaults([
'data_class' => Review::class,
]);
}
}
