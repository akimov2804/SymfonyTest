<?php

namespace App\Controller;

use App\Entity\Match;
use App\Entity\Team;
use App\Form\MatchType;
use App\Form\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        $team = new Team();
        $match = new Match();
        $formteam = $this->createForm(TeamType::class, $team);
        $formmatch = $this->createForm(MatchType::class, $match);
        $formteam->handleRequest($request);
        $formmatch->handleRequest($request);
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'formteam' => $formteam->createView(),
            'formmatch' => $formmatch->createView()
        ]);
    }
}
