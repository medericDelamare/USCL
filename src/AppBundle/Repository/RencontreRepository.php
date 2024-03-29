<?php

namespace AppBundle\Repository;


use AppBundle\Entity\Equipe;
use AppBundle\Entity\Rencontre;
use Doctrine\ORM\EntityRepository;

class RencontreRepository extends EntityRepository
{
    public function getRencontresByCategorie($categorie){
        $query = $this->createQueryBuilder('r')
            ->select('r')
            ->join('r.equipeDomicile', 'd', 'WITH', 'd.id = r.equipeDomicile')
            ->leftJoin('r.equipeExterieure', 'e', 'WITH', 'e.id = r.equipeExterieure')
            ->where('d.categorie = :categorie')
            ->orWhere('e.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->getQuery();

        return $query->getResult();
    }

    public function getRencontreByEquipeAndCategorie($equipe1, $equipe2, $categorie){
        return $this->createQueryBuilder('r')
            ->join('r.equipeDomicile', 'd', 'WITH', 'd.id = r.equipeDomicile')
            ->leftJoin('r.equipeExterieure', 'e', 'WITH', 'e.id = r.equipeExterieure')
            ->where('d.categorie = :categorie')
            ->andWhere('d.nomParse = :equipe1')
            ->andWhere('e.nomParse = :equipe2')
            ->setParameters(['categorie' => $categorie, 'equipe1' => $equipe1, 'equipe2' => $equipe2])
            ->getQuery()
            ->getResult();
    }

    public function getRencontreByEquipeByJourneeAndCategorie($equipe1, $equipe2, $journee ,$categorie){
        return $this->createQueryBuilder('r')
            ->join('r.equipeDomicile', 'd', 'WITH', 'd.id = r.equipeDomicile')
            ->leftJoin('r.equipeExterieure', 'e', 'WITH', 'e.id = r.equipeExterieure')
            ->where('d.categorie = :categorie')
            ->andWhere('r.journee = :journee')
            ->andWhere('d.nomParse = :equipe1')
            ->andWhere('e.nomParse = :equipe2')
            ->setParameters(['categorie' => $categorie, 'equipe1' => $equipe1, 'equipe2' => $equipe2, 'journee' => $journee])
            ->getQuery()
            ->getResult();
    }

    public function getDerniereJournee($categorie, $debutSaison, $finSaison){
        $derniereJournee = $this->createQueryBuilder('r')
            ->select('r.journee')
            ->join(Equipe::class, 'd', 'WITH', 'd.id = r.equipeDomicile')
            ->where('d.categorie = :categorie')
            ->andWhere('r.score IS NOT NULL')
            ->andWhere('r.date > :debutSaison')
            ->andwhere('r.date < :finSaison')
            ->orderBy('r.date', 'DESC')
            ->setParameters(['categorie'=> $categorie, 'debutSaison' => $debutSaison, 'finSaison' => $finSaison])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $rencontres = $this->createQueryBuilder('r')
            ->join('r.equipeDomicile', 'd', 'WITH', 'd.id = r.equipeDomicile')
            ->where('r.journee = :derniereJournee')
            ->andWhere('d.categorie = :categorie')
            ->andWhere('r.date > :debutSaison')
            ->andwhere('r.date < :finSaison')
            ->setParameters(['categorie'=> $categorie, 'debutSaison' => $debutSaison, 'finSaison' => $finSaison,'derniereJournee' => $derniereJournee['journee']])
            ->getQuery()
            ->getResult();
        
        return $rencontres;
    }

    public function getAgenda($categorie){
        $agendas = $rencontres = $this->createQueryBuilder('r')
            ->join('r.equipeDomicile', 'd', 'WITH', 'd.id = r.equipeDomicile')
            ->where('r.date < :semaine')
            ->andWhere('r.date > :dateCourante')
            ->andWhere('d.categorie LIKE :categorie')
            ->setParameter('categorie', $categorie)
            ->setParameter('dateCourante', new \DateTime( ))
            ->setParameter('semaine', new \DateTime('+1 week'))
            ->getQuery()
            ->getResult();

        return $agendas;
    }

    public function getWeekGames(){
        $agendas = $rencontres = $this->createQueryBuilder('r')
            ->join('r.equipeDomicile', 'd', 'WITH', 'd.id = r.equipeDomicile')
            ->leftJoin('r.equipeExterieure', 'e', 'WITH', 'e.id = r.equipeExterieure')
            ->where('r.date < :semaine')
            ->andWhere('r.date > :dateCourante')
            ->andWhere('d.club = 1 OR e.club = 1')
            ->setParameter('dateCourante', new \DateTime())
            ->setParameter('semaine', new \DateTime('+1 week'))
            ->orderBy('r.date', 'ASC')
            ->getQuery()
            ->getResult();
        return $agendas;
    }

    public function getCalendrierParCategorie($categorie, $debutSaison, $finSaison){
        return $this->createQueryBuilder('r')
            ->join(Equipe::class, 'd', 'WITH', 'd.id = r.equipeDomicile')
            ->where('d.categorie = :categorie')
            ->andWhere('r.date > :debutSaison')
            ->andwhere('r.date < :finSaison')
            ->orderBy('r.date', 'ASC')
            ->setParameters(['categorie'=> $categorie, 'debutSaison' => $debutSaison, 'finSaison' => $finSaison])
            ->getQuery()
            ->getResult();
    }

    public function getCalendrierParEquipe($equipe){
        return $this->createQueryBuilder('r')
            ->join('r.equipeDomicile', 'd', 'WITH', 'd.id = r.equipeDomicile')
            ->leftJoin('r.equipeExterieure', 'e', 'WITH', 'e.id = r.equipeExterieure')
            ->where('d.id = :equipe')
            ->orWhere('e.id = :equipe')
            ->orderBy('r.date', 'ASC')
            ->setParameter('equipe', $equipe)
            ->getQuery()
            ->getResult();
    }

    public function getLastFiveRencontreByEquipe($equipe){
        return $this->createQueryBuilder('r')
            ->join('r.equipeDomicile', 'd', 'WITH', 'd.id = r.equipeDomicile')
            ->leftJoin('r.equipeExterieure', 'e', 'WITH', 'e.id = r.equipeExterieure')
            ->where('d.id = :equipe')
            ->orWhere('e.id = :equipe')
            ->andWhere('r.score IS NOT NULL')
            ->orderBy('r.date', 'DESC')
            ->setParameter('equipe', $equipe)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}