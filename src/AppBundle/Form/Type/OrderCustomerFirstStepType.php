<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderCustomerFirstStepType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', RepeatedType::class, array(
                'type' => EmailType::class,
                'invalid_message' => 'Les adresses mails doivent correspondre',
                'required' => true
            ))
            ->add('nbTickets', IntegerType::class, array(
                'attr' => array('min' => 1, 'max' => 10)))
            ->add('visitDate', DateType::class, array(
                'widget' => 'single_text',
            ))
            ->add('duration', ChoiceType::class, array(
                'choices' => array(
                    'Journée' => 'journée',
                    'Demi-journée' => 'demi-journée'),
                'choice_attr' => function($key) {
                    return ['class' => 'toggle radio-'.$key.' toggle-'.$key];

                },
                'expanded' => true,
                'multiple' => false
            ))
            ->add('save', SubmitType::class);
    }
}
