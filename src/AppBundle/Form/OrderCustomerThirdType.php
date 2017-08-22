<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;


class OrderCustomerThirdType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->remove('email')
            ->remove('nbTickets')
            ->remove('tickets')
            ->add('tickets', CollectionType::class, array(
                'entry_type' => TicketThirdType::class,
                'allow_add' => true,
                'allow_delete' => true
            ));
    }

    public function getParent()
    {
        return OrderCustomerType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ordercustomerthird';
    }


}
