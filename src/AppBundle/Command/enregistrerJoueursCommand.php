<?php


namespace AppBundle\Command;


use AppBundle\Entity\CarriereJoueur;
use AppBundle\Entity\Licencie;
use AppBundle\Entity\NomParse;
use AppBundle\Entity\SousCategorie;
use AppBundle\Entity\StatsJoueur;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class enregistrerJoueursCommand extends ContainerAwareCommand
{

    private $logs = [];

    const NUMERO_CLUB = '550717';
    const START_SEASON = '2021';


    protected function configure()
    {
        $this
            ->setName('enregistrer:joueurs')
            ->setDescription('Récupération des joueurs');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
        
        $json_data = file_get_contents($this->getContainer()->get('kernel')->getRootDir() . '/Resources/Documents/LicenciesJson/uscl-'. self::START_SEASON.'.json');

        $data = json_decode($json_data, true);

        $nbCreation = 0;
        $nbMaj = 0;

        $nouveauJoueur = false;


        foreach ($data as $joueur) {
            if ($this->hasLicenceValide($joueur['permits'])) {
                $doctrine = $this->getContainer()->get('doctrine');
                /** @var EntityManager $em */
                $em = $doctrine->getManager();

                if (!$licencie = $em->getRepository(Licencie::class)->findOneByNumeroLicence((integer)$joueur['id'])) {
                    $licencie = new Licencie();
                }

                $licencie->getId() ? $nbMaj++ : $nbCreation++;

                $simpleDate = explode('T', $joueur['birthday']['date']);

                array_key_exists('Mobile personnel', $joueur['contacts']) ? $mobile = $joueur['contacts']['Mobile personnel'][0] : $mobile = null;
                array_key_exists('Email principal', $joueur['contacts']) ? $email = $joueur['contacts']['Email principal'][0] : $email = null;
                array_key_exists('Téléphone domicile', $joueur['contacts']) ? $fixe = $joueur['contacts']['Téléphone domicile'][0] : $fixe = null;


                if ($this->getCategorie($joueur) != null && !$sousCategorie = $em->getRepository(SousCategorie::class)->findOneByNom($this->getCategorie($joueur))) {
                    $sousCategorie = new SousCategorie();
                    $sousCategorie->setNom($this->getCategorie($joueur));
                    $em->persist($sousCategorie);
                    $em->flush();
                }

                $licencie
                    ->setNumeroLicence((integer)$joueur['id'])
                    ->setCategorie($this->getCategorie($joueur))
                    ->setNom($joueur['lastName'])
                    ->setPrenom($joueur['firstName'])
                    ->setNationalite($joueur['nationality'])
                    ->setDateDeNaissance(\DateTime::createFromFormat('Y-m-d', $simpleDate[0]))
                    ->setLieuDeNaissance(ucfirst($joueur['birthday']['birthPlace']))
                    ->setAdresse($joueur['address']['city'])
                    ->setPhoto($joueur['photo']['link'])
                    ->setTelephoneDomicile($fixe)
                    ->setTelephonePortable($mobile)
                    ->setEmail($email)
                    ->setJoueur($this->isJoueur($joueur))
                    ->setDirigeant($this->isDirigeant($joueur))
                    ->setEducateur($this->isEducateur($joueur));

                foreach ($licencie->getCarriere() as $carriereJoueur) {
                    $em->remove($carriereJoueur);
                }

                $em->flush();

                $carriereParSaison = [];

                foreach ($joueur['permits'] as $licence) {
                    $carriereParSaison[substr($licence['startSeason'], 0, 4) . '-' . substr($licence['endSeason'], 0, 4)][$licence['club']['name']][] = $licence['subCategory'];
                }

                foreach ($carriereParSaison as $annee => $clubArray) {
                    foreach ($clubArray as $club => $categArray) {
                        $carriere = new CarriereJoueur();

                        $carriere->setSaison($annee);

                        $entityClub = $em->getRepository(NomParse::class)->findOneByNom($club);
                        is_null($entityClub) ? $this->logs[] = $club : $carriere->setClub($entityClub->getClub());

                        $carriere->setClubParse($club);
                        foreach ($categArray as $key => $categ) {
                            $carriere->getSousCategorie() ? $carriere->setSousCategorie($carriere->getSousCategorie() . ' - ' . $categ) : $carriere->setSousCategorie($categ);
                        }
                        $licencie->addCarriere($carriere);
                    }
                }

                $em->persist($licencie);
                $em->flush();
            }
        }


        dump(array_unique($this->logs));
        $output->writeln('<comment>Il y a ' . $nbCreation . ' créations et ' . $nbMaj . ' MAJ ---</comment>');

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    private function isJoueur($joueur)
    {
        foreach ($joueur['permits'] as $licence) {
            if ($this->isValidee($licence) && $this->isSaisonCourante($licence) && $this->isCormeillesId($licence) && strpos($licence['subCategory'], 'Libre') !== false) {
                return true;
            }
        }

        return false;
    }

    private function isDirigeant($joueur)
    {
        foreach ($joueur['permits'] as $licence) {
            if ($this->isValidee($licence) && $this->isSaisonCourante($licence) && $this->isCormeillesId($licence) && strpos($licence['subCategory'], 'Dirigeant') !== false) {
                return true;
            }
        }

        return false;
    }

    private function isEducateur($joueur)
    {
        foreach ($joueur['permits'] as $licence) {
            if ($this->isValidee($licence) && $this->isSaisonCourante($licence) && $this->isCormeillesId($licence) && strpos($licence['subCategory'], 'Educateur') !== false) {
                return true;
            }
        }

        return false;
    }

    private function getCategorie($joueur)
    {
        foreach ($joueur['permits'] as $licence) {
            if ($this->getContainer()->getParameter('debut_annee') == substr($licence['startSeason'], 0, 4) && strpos($licence['subCategory'], 'Libre') !== false) {
                return $licence['subCategory'];
            }
        }
    }

    private function isValidee($licence)
    {
        if ($licence['state'] == 'Validée') {
            return true;
        }

        return false;
    }

    private function isCormeillesId($licence)
    {
        if ($licence['club']['id'] == self::NUMERO_CLUB) {
            return true;
        }

        return false;
    }

    private function isSaisonCourante($licence)
    {
        if (substr($licence['startSeason'], 0, 4) == self::START_SEASON) {
            return true;
        }

        return false;
    }

    private function hasLicenceValide($licences)
    {
        foreach ($licences as $licence) {
            if ($this->isValidee($licence) && $this->isSaisonCourante($licence) && $this->isCormeillesId($licence)) {
                return true;
            }
        }

        return false;
    }
}