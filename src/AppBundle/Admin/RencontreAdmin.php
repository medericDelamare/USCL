<?php

namespace AppBundle\Admin;


use AppBundle\Entity\StatsRencontre;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RencontreAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('rencontre')
            ->add('joueurs')
            ->add('cartonsJaunes')
            ->add('cartonsRouges')
            ->add('buteurs', 'sonata_type_collection', [
                'label' => false,
                'btn_add' => 'Ajouter un bloc'
            ],[
                'edit' => 'inline',
                'inline' => 'table',
            ])
        ;
    }
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->addIdentifier('id')
            ->add('rencontre')
        ;
    }
}