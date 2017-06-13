<?php

namespace AppBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ClubAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('president')
            ->add('vicePresident1')
            ->add('vicePresident2')
            ->add('vicePresident3')
            ->add('secretaire')
            ->add('tresorier')
            ->add('responsableJeunes')
            ->add('responsableSeniors');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('president')
            ->add('vicePresident1')
            ->add('vicePresident2')
            ->add('vicePresident3')
            ->add('secretaire')
            ->add('tresorier')
            ->add('responsableJeunes')
            ->add('responsableSeniors');
    }
}