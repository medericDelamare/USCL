<?php


namespace AppBundle\Admin;


use Doctrine\DBAL\Types\StringType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EquipeAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nomParse')
            ->add('nom',null,[])
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Equipe A' => 'seniorA',
                    'Equipe B' => 'seniorB',
                    'Fémninines' => 'seniorF',
                    'U18' => 'U18',
                    'U15' => 'U15',
                    'U13 A' => 'U13A',
                    'U13 B' => 'U13B',
                    'Veterans A' => 'veteranA',
                    'Veterans B' => 'veteranB',
                    'Coupe de France' => 'coupeDeFrance',
                    'Coupe de Normandie' => 'coupeDeNormandie',
                    'Coupe de l\'Eure' => 'coupeEure',
                    'Coupe U18' => 'coupeU18',
                    'Coupe U15' => 'coupeU15',
                    'Coupe Veterans' => 'coupeVeterans',
                    'Coupe Des Reserves' => 'coupeReserves',
                    'U18 Phase 2' => 'U18-phase2',
                    'U15 Phase 2' => 'U15-phase2',
                    'U13A Phase 2' => 'U13A-phase2',
                    'U13B Phase 2' => 'U13B-phase2',
                ]
            ])
            ->add('club')
            ->add('codeScorenco')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('nom_parse')
            ->add('categorie')
            ->add('club')
            ->add('codeScorenco')
        ;
    }
}