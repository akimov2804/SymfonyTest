<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Games;
use App\Entity\Team;

class StatController extends AbstractController
{
    /**
     * @Route("/stat", name="stat")
     */
    public function index()
    {
        $statistics = array();
        for($i = 1; $i < 21; $i ++) {
            $teamStatistics = array();
            $countHomeWins = 0;
            $countAwayWins = 0;
            $countDraws = 0;
            $countHomeGames = 0;
            $countAwayGames = 0;
            $countGames = 0;
            $team = $this->getDoctrine()
                ->getRepository(Team::class)
                ->find($i)->getNumber();
            $teamName = $this->getDoctrine()
                ->getRepository(Team::class)
                ->find($i)->getName();
            $teamLogo = $this->getDoctrine()
                ->getRepository(Team::class)
                ->find($i)->getLogo();
            $homeGames = $this->getDoctrine()
                ->getRepository(Games::class)
                ->findBy(['HomeTeam' => $team]);
            for ($j =0; $j < count($homeGames); $j++){
                $final = $homeGames[$j]->getFinal();
                $home = (int)$final{0};
                $away = (int)$final{2};
                if ($home > $away)
                    $countHomeWins++;
                elseif ($home == $away)
                    $countDraws++;
                $countHomeGames++;
                $countGames++;
            }
            $awayGames = $this->getDoctrine()
                ->getRepository(Games::class)
                ->findBy(['AwayTeam' => $team]);
            for ($k =0; $k < count($awayGames); $k++){
                $final = $awayGames[$k]->getFinal();
                $home = (int)$final{0};
                $away = (int)$final{2};
                if ($home < $away)
                    $countAwayWins++;
                elseif ($home == $away)
                    $countDraws++;
                $countAwayGames++;
                $countGames++;
            }
            $percentHomeWins = $countHomeWins / $countHomeGames * 100;
            $percentAwayWins = $countAwayWins / $countAwayGames * 100;
            $percentDraws = $countDraws / $countGames * 100;
            array_push($teamStatistics, $teamName, $teamLogo, round($percentHomeWins, 2), round($percentAwayWins, 2), round($percentDraws, 2));
            array_push($statistics, $teamStatistics);
        }
        return $this->render('stat/index.html.twig', [
            'Statistics' => $statistics,
        ]);
    }
}
