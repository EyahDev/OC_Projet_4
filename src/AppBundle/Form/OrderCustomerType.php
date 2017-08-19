<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderCustomerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Création d'une interface de session
        $session = new Session();

        // Formulaire
        $builder
            ->add('email')
            ->add('nbTickets', IntegerType::class, array(
                'attr' => array('min' => 0),
                'data' => $session->get('nbTickets')
            ))
            ->add('price')
            ->add('orderToken')
            ->add('orderDate');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\OrderCustomer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ordercustomer';
    }


}
