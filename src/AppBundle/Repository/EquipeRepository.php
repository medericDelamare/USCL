<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Equipe;
use AppBundle\Entity\StatsEquipe;
use Doctrine\ORM\EntityRepository;

class EquipeRepository extends EntityRepository
{
    public function getClassementByCategorie($categorie, $saison){
        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Equipe::class, 'c');

        $sql = "SELECT * FROM equipe WHERE equipe.categorie =:categorie AND saison =:saison ORDER BY equipe.stats_points DESC, equipe.stats_difference DESC, equipe.stats_buts_pour DESC ";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm)
            ->setParameter('categorie', $categorie)
            ->setParameter('saison', $saison);
        return $query->getResult();
    }

    public function resetStats($categorie){
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("UPDATE equipe SET 
            equipe.stats_points = 0, 
            equipe.stats_journees = 0, 
            equipe.stats_victoires = 0, 
            equipe.stats_nuls = 0, 
            equipe.stats_defaites = 0, 
            equipe.stats_forfaits = 0, 
            equipe.stats_buts_pour = 0,
            equipe.stats_buts_contre = 0,
            equipe.stats_penalites = 0,
            equipe.stats_difference = 0
            WHERE equipe.categorie = :categorie
            ");
        $statement->bindValue('categorie', $categorie);
        $statement->execute();
    }

    public function findByCategorieOrderByNomParse($categorie, $saison){
        return $this->createQueryBuilder('e')
            ->where('e.categorie = :categorie')
            ->andWhere('e.saison = :saison')
            ->orderBy('e.nomParse')
            ->setParameters(['categorie' => $categorie, 'saison' => $saison])
            ->getQuery()
            ->getResult();
    }

    public function findByCategorieAndCodeScorenco($categorie, $code){
        return $this->createQueryBuilder('e')
            ->where('e.categorie = :categorie')
            ->andWhere('e.codeScorenco = :score')
            ->orderBy('e.nomParse')
            ->setParameters(['categorie' => $categorie, 'score' => $code])
            ->getQuery()
            ->getOneOrNullResult();
    }
}