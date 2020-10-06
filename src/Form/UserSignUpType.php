<?php

/*
 * Form for signing-up
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
//use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
/**
 * Defines the form used to register an user.
 *
 * @author Christophe Mathiou <christophe.mathiou@gmail.com>
 */
class UserSignUpType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // For the full reference of options defined by each form field type
        // see https://symfony.com/doc/current/reference/forms/types.html

        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        // $builder->add('title', null, ['required' => false, ...]);

        $builder
            ->add('username', TextType::class, [
                'label' => 'label.username',
            ])
            ->add('fullName', TextType::class, [
                'label' => 'label.fullname',
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'max' => 128,
                    ]),
                ],
                'label' => 'label.password',
            ])
            /**
             * TODO un-comment for prod and comment above
             */
//            ->add('password', RepeatedType::class, [
//                'type' => PasswordType::class,
//                'constraints' => [
//                    new NotBlank(),
//                    new Length([
//                        'min' => 5,
//                        'max' => 128,
//                    ]),
//                ],
//                'first_options' => [
//                    'label' => 'label.password',
//                ],
//                'second_options' => [
//                    'label' => 'label.password_confirm',
//                ],
//            ])
            ->add('image', ImageType::class, [
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
