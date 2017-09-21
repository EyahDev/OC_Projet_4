<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class TicketSecondStepType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name',TextType::class, array(
                'invalid_message' => 'validator.step.2.name.valid'
            ))
            ->add('lastName', TextType::class, array(
                'invalid_message' => 'validator.step.2.lastname.valid'
            ))
            ->add('age', BirthdayType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/y',
                'invalid_message' => 'validator.step.2.age.valid'
            ))
            ->add('country', CountryType::class, array(
                'placeholder' => 'appbundle.step.2.form.placeholder.country',
                'invalid_message' => 'validator.step.2.country.valid'

            ))
            ->add('reduced_price', CheckboxType::class, array(
                'label' => 'appbundle.step.2.form.placeholder.reduced.price',
                'label_attr' => array( 'class' => 'btn'),
                'required' => false
            ));
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket',
        ));
    }
}
