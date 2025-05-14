<?php
// src/Form/RegistrationFormType.php
namespace App\Form;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'First name'])
            ->add('lastName', TextType::class, ['label' => 'Last name'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Password',
                'attr' => ['autocomplete' => 'new-password'],
            ])
            ->add('birthDate', BirthdayType::class, [
                'widget' => 'single_text',
                'label'  => 'Date of birth',
            ])
            ->add('phone', TelType::class, [ 'label' => 'Phone', 'required' => false ])
            ->add('address', TextType::class, [ 'label' => 'Address' ])
            ->add('postalCode', TextType::class, [ 'label' => 'Postal code', 'required' => false ])
            ->add('city', TextType::class, [ 'label' => 'City', 'required' => false ])
            ->add('country', CountryType::class, [ 'label' => 'Country', 'required' => false ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
