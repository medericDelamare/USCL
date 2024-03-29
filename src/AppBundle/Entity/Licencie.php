<?php



namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Licencié
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LicencieRepository")
 *
 */
class Licencie
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="bigint", nullable=false, unique=true)
     */
    private $numeroLicence;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $nom;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $prenom;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $categorie;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $nationalite;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateDeNaissance;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $lieuDeNaissance;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $adresse;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $joueur;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $dirigeant;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $educateur;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $telephoneDomicile;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $telephonePortable;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $photo;

    /**
     * @var CarriereJoueur[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CarriereJoueur", mappedBy="licencie", cascade={"all"})
     * @ORM\OrderBy({"saison" = "DESC"})
     */
    private $carriere;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\StatsRencontre", mappedBy="joueurs", fetch="EAGER")
     */
    private $statsRencontresJoueurs;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\StatsRencontre", mappedBy="cartonsJaunes")
     */
    private $statsRencontresCartonsJaunes;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\StatsRencontre", mappedBy="cartonsRouges")
     */
    private $statsRencontresCartonsRouges;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="But", mappedBy="buteur")
     */
    private $buts;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="But", mappedBy="passeur")
     */
    private $passes;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\HistoriqueStats", mappedBy="licencie", cascade={"persist"})
     *
     */
    private $historiqueStats;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Poste")
     * @ORM\JoinColumn(nullable=true)
     */
    private $poste;
    private $nbMatchs = 0;
    private $nbButs = 0;

    public function __construct()
    {
        $this->carriere = new ArrayCollection();
        $this->statsRencontresJoueurs = new ArrayCollection();
        $this->statsRencontresCartonsJaunes = new ArrayCollection();
        $this->statsRencontresCartonsRouges = new ArrayCollection();
        $this->buts = new ArrayCollection();
        $this->passes = new ArrayCollection();
        $this->historiqueStats = new ArrayCollection();
        $this->joueur = true;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNumeroLicence()
    {
        return $this->numeroLicence;
    }

    /**
     * @param int $numeroLicence
     * @return Licencie
     */
    public function setNumeroLicence($numeroLicence)
    {
        $this->numeroLicence = $numeroLicence;
        return $this;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return Licencie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     * @return Licencie
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param string $categorie
     * @return Licencie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return string
     */
    public function getNationalite()
    {
        return $this->nationalite;
    }

    /**
     * @param string $nationalite
     * @return Licencie
     */
    public function setNationalite($nationalite)
    {
        $this->nationalite = $nationalite;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateDeNaissance()
    {
        return $this->dateDeNaissance;
    }

    /**
     * @param string $dateDeNaissance
     * @return Licencie
     */
    public function setDateDeNaissance($dateDeNaissance)
    {
        $this->dateDeNaissance = $dateDeNaissance;
        return $this;
    }

    /**
     * @return string
     */
    public function getLieuDeNaissance()
    {
        return $this->lieuDeNaissance;
    }

    /**
     * @param string $lieuDeNaissance
     * @return Licencie
     */
    public function setLieuDeNaissance($lieuDeNaissance)
    {
        $this->lieuDeNaissance = $lieuDeNaissance;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     * @return Licencie
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return bool
     */
    public function isJoueur()
    {
        return $this->joueur;
    }

    /**
     * @param bool $joueur
     * @return Licencie
     */
    public function setJoueur($joueur)
    {
        $this->joueur = $joueur;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDirigeant()
    {
        return $this->dirigeant;
    }

    /**
     * @param bool $dirigeant
     * @return Licencie
     */
    public function setDirigeant($dirigeant)
    {
        $this->dirigeant = $dirigeant;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEducateur()
    {
        return $this->educateur;
    }

    /**
     * @param bool $educateur
     * @return Licencie
     */
    public function setEducateur($educateur)
    {
        $this->educateur = $educateur;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelephoneDomicile()
    {
        return $this->telephoneDomicile;
    }

    /**
     * @param string $telephoneDomicile
     * @return Licencie
     */
    public function setTelephoneDomicile($telephoneDomicile)
    {
        $this->telephoneDomicile = $telephoneDomicile;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelephonePortable()
    {
        return $this->telephonePortable;
    }

    /**
     * @param string $telephonePortable
     * @return Licencie
     */
    public function setTelephonePortable($telephonePortable)
    {
        $this->telephonePortable = $telephonePortable;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Licencie
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     * @return Licencie
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCarriere()
    {
        return $this->carriere;
    }

    /**
     * @param CarriereJoueur[] $carriere
     * @return Licencie
     */
    public function setCarriere($carriere)
    {
        $this->carriere = $carriere;
        return $this;
    }

    /**
     * @param CarriereJoueur $carriere
     */
    public function addCarriere($carriere){
        if (!$this->carriere->contains($carriere)){
            $this->carriere->add($carriere);
            $carriere->setLicencie($this);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getStatsRencontresJoueurs()
    {
        return $this->statsRencontresJoueurs->filter(function(StatsRencontre $statsRencontre) {
            return
                $statsRencontre->getRencontre()->getDate() > \DateTime::createFromFormat('Y-m-d', '2020-06-15') &&
                $statsRencontre->getRencontre()->getDate() < \DateTime::createFromFormat('Y-m-d', '2021-06-15') ;
        });
    }

    /**
     * @param mixed $statsRencontresJoueurs
     * @return Licencie
     */
    public function setStatsRencontresJoueurs($statsRencontresJoueurs)
    {
        $this->statsRencontresJoueurs = $statsRencontresJoueurs;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatsRencontresCartonsJaunes()
    {
        return $this->statsRencontresCartonsJaunes->filter(function(StatsRencontre $statsRencontre) {
            return
                $statsRencontre->getRencontre()->getDate() > \DateTime::createFromFormat('Y-m-d', '2020-06-15') &&
                $statsRencontre->getRencontre()->getDate() < \DateTime::createFromFormat('Y-m-d', '2021-06-15') ;
        });
    }

    /**
     * @param mixed $statsRencontresCartonsJaunes
     * @return Licencie
     */
    public function setStatsRencontresCartonsJaunes($statsRencontresCartonsJaunes)
    {
        $this->statsRencontresCartonsJaunes = $statsRencontresCartonsJaunes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatsRencontresCartonsRouges()
    {
        return $this->statsRencontresCartonsRouges->filter(function(StatsRencontre $statsRencontre) {
            return
                $statsRencontre->getRencontre()->getDate() > \DateTime::createFromFormat('Y-m-d', '2020-06-15') &&
                $statsRencontre->getRencontre()->getDate() < \DateTime::createFromFormat('Y-m-d', '2021-06-15') ;
        });
    }

    /**
     * @param mixed $statsRencontresCartonsRouges
     * @return Licencie
     */
    public function setStatsRencontresCartonsRouges($statsRencontresCartonsRouges)
    {
        $this->statsRencontresCartonsRouges = $statsRencontresCartonsRouges;
        return $this;
    }

    public function getNomComplet(){
        return $this->getNom()  .  ' ' . $this->getPrenom();
    }

    /**
     * @return ArrayCollection
     */
    public function getButs()
    {
        return $this->buts->filter(function(But $but) {
            return
                $but->getStatsRencontres()->getRencontre()->getDate() > \DateTime::createFromFormat('Y-m-d', '2020-06-15') &&
                $but->getStatsRencontres()->getRencontre()->getDate() < \DateTime::createFromFormat('Y-m-d', '2021-06-15') ;
        });
    }

    /**
     * @param ArrayCollection $buts
     * @return Licencie
     */
    public function setButs($buts)
    {
        $this->buts = $buts;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPasses()
    {
        return $this->passes->filter(function(But $but) {
            return
                $but->getStatsRencontres()->getRencontre()->getDate() > \DateTime::createFromFormat('Y-m-d', '2020-06-15') &&
                $but->getStatsRencontres()->getRencontre()->getDate() < \DateTime::createFromFormat('Y-m-d', '2021-06-15') ;
        });
    }

    /**
     * @param ArrayCollection $passes
     * @return Licencie
     */
    public function setPasses($passes)
    {
        $this->passes = $passes;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getHistoriqueStats()
    {
        return $this->historiqueStats;
    }

    /**
     * @param ArrayCollection $historiqueStats
     * @return Licencie
     */
    public function setHistoriqueStats($historiqueStats)
    {
        /** @var HistoriqueStats $historiqueStat */
        foreach ($historiqueStats as $historiqueStat){
            $historiqueStat->setLicencie($this);
        }

        $this->historiqueStats = $historiqueStats;
    }

    /**
     * @param HistoriqueStats $historiqueStats
     * @return Licencie
     */
    public function addHistoriqueStat(HistoriqueStats $historiqueStats){
        $this->historiqueStats->add($historiqueStats);
        $historiqueStats->setLicencie($this);
        return $this;
    }

    public function getAge(){
        $birthDate = $this->getDateDeNaissance();
        $to   = new \DateTime('today');
        return $birthDate->diff($to)->y;
    }

    public function getInitiales(){
        return substr($this->getPrenom(),0,1) . substr($this->getNom(),0,1);
    }

    public function getMatchsVeteran(){
        $arr = [];
        /** @var StatsRencontre $rencontresJoueur */
        foreach ($this->getStatsRencontresJoueurs() as $rencontresJoueur){
            if (in_array($rencontresJoueur->getRencontre()->getEquipeDomicile()->getCategorie(), ['veteranA','coupeVeterans'])){
                $arr[] = $rencontresJoueur;
            }
        }

        return $arr;
    }

    /**
     * @return string
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * @param string $poste
     * @return Licencie
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbMatchs()
    {
        return $this->nbMatchs;
    }

    /**
     * @param int $nbMatchs
     * @return Licencie
     */
    public function setNbMatchs($nbMatchs)
    {
        $this->nbMatchs = $nbMatchs;
        return $this;
    }

    public function incrementNbMatch(){
        $this->nbMatchs ++;
    }

    /**
     * @return int
     */
    public function getNbButs()
    {
        return $this->nbButs;
    }

    /**
     * @param int $nbButs
     * @return Licencie
     */
    public function setNbButs($nbButs)
    {
        $this->nbButs = $nbButs;
        return $this;
    }

    public function incrementNbButs(){
        $this->nbButs ++;
    }

    public function __toString()
    {
        return $this->getNomComplet();
    }
}