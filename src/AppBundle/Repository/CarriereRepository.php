<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Categorie;
use AppBundle\Entity\Licencie;
use AppBundle\Entity\SousCategorie;
use AppBundle\Entity\StatsJoueur;
use Doctrine\ORM\EntityRepository;

class CarriereRepository extends EntityRepository
{
    public function nbLicencie($saison){
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where("c.saison = :saison")
            ->andWhere("c.clubParse LIKE 'U.S. CORMEILLES - LIEUREY' ")
            ->setParameter('saison', $saison)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findLicencieBySaisonAndCategorie($categorie, $saison, $poste)
    {
        return $this
            ->createQueryBuilder('carriere')
            ->select('l')
            ->leftJoin(Licencie::class, 'l', 'WITH', 'carriere.licencie = l.id')
            ->leftjoin(SousCategorie::class, 'sc', 'WITH', 'sc.nom = l.categorie')
            ->leftjoin(Categorie::class, 'c', 'WITH', 'sc.categorie = c.id')
            ->where('c.nom LIKE :categorie')
            ->andWhere('l.poste IS NOT NULL')
            ->andWhere('carriere.saison = :saison')
            ->andWhere('l.poste = :poste' )
            ->setParameter('categorie', $categorie)
            ->setParameter('saison', $saison)
            ->setParameter('poste', $poste)
            ->getQuery()
            ->getResult();
    }

    public function findLicencieByCategoryAndsaison($categorie, $saison)
    {
        return $this->createQueryBuilder('c')
            ->select('l')
            ->leftJoin(Licencie::class, 'l', 'WITH', 'c.licencie = l.id')
            ->leftjoin(SousCategorie::class, 'sc', 'WITH', 'sc.nom = l.categorie')
            ->leftjoin(Categorie::class, 'categ', 'WITH', 'sc.categorie = categ.id')
            ->where('categ.nom LIKE :category')
            ->andWhere('c.saison LIKE :saison')
            ->setParameter('category', $categorie)
            ->setParameter('saison', $saison)
            ->getQuery()
            ->getResult();
    }



}