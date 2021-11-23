<?php

namespace AppBundle\Controller;


use AppBundle\Service\Scorenco\ScorencoService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StatsController extends Controller
{
    /**
     * @Route("/statistiques/{category}", name="statistiques")
     * @Template()
     */
    public function showAction(Request $request, $category)
    {
        /** @var ScorencoService $scorencoService */
        $scorencoService = $this->container->get('scorenco.scorenco_service');
        $competitionId = $this->getParameter('competition')[$category];


        $classement = $scorencoService->getClassementByUrl($competitionId);
        $distinctEquipes = $scorencoService->getDisctinctTeams("https://scorenco.com/backend/v1/competitions/" . $competitionId . "/rankings/");
        $infosCompetition = $scorencoService->getInformationsCompetition("https://scorenco.com/backend/v1/competitions/" . $competitionId . "/");
        $resultats = $scorencoService->getLastResultsOfComeptition($competitionId);
        $agendas = $scorencoService->getAgendasOfCompetition($competitionId);

        return $this->render(':default:statistiques.html.twig', [
            'class' => 'business-header-' . $category,
            'tableClass' => 'classement-' . $category,
            'rencontres' => $resultats,
            'agendas' => $agendas,
            'equipeListe' => $distinctEquipes,
            'base_rul' => $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath(),
            'infosCompetition' => $infosCompetition,
            'classement' => $classement,
            'competitionId' => $competitionId
        ]);
    }


    /**
     * @Route("get-calendrier-par-equipe/{teamId}/{competitionId}", name="calendrier-par-equipe")
     * @Template()
     */
    public function calendrierParEquipe(Request $request, $teamId = null, $competitionId = null)
    {
        /** @var ScorencoService $scorencoService */
        $scorencoService = $this->container->get('scorenco.scorenco_service');
        $calendrierParEquipe = $scorencoService->getCalendrierByEquipeAndCompetition($teamId, $competitionId);
        return $this->render('default/tableau-calendrier.html.twig', [
            'equipes' => $calendrierParEquipe
        ]);
    }
}