<?php

namespace App\Controller;

use App\Entity\Match;
use App\Entity\Team;
use App\Entity\Games;
use App\Form\MatchType;
use App\Form\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        $array = array();
        $allTeams = array();
        for($i = 1; $i < 21; $i++) {
            $array = array();
            $team = $this->getDoctrine()->getRepository(Team::class)->find($i);
            array_push($array, $team->getId(), $team->getName(), $team->getLogo(), $team->getNumber());
            array_push($allTeams, $array);
        }
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'Teams' => $allTeams
        ]);
    }
}
