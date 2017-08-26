<?php

namespace AppBundle\Form;

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
        $date = new \DateTime();

        $builder
            ->add('email', RepeatedType::class, array(
                'type' => EmailType::class,
                'invalid_message' => 'Les adresses mails doivent correspondre',
                'required' => true
            ))
            ->add('nbTickets', IntegerType::class, array(
                'attr' => array('min' => 0, 'max' => 10)))
            ->add('visitDate', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/y',
                'data' => $date
            ))
            ->add('duration', ChoiceType::class, array(
                'choices' => array(
                    'Journée' => 'journée',
                    'Demi-journée' => 'demi-journée'),
                'expanded' => true,
                'multiple' => false
            ))
            ->add('save', SubmitType::class);
    }
}
