<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Games;

class PredictController extends AbstractController
{
    /**
     * @Route("/predict", name="predict")
     */
    public function index(Request $request)
    {
        $HomeMatchesResults = 0;
        $AwayMatchesResults = 0;
        $id = (int)$request->query->get('id');
        $HomeTeam = $this->getDoctrine()
            ->getRepository(Games::class)
            ->findBy(['HomeTeam' => $id]);
        $AwayTeam = $this->getDoctrine()
            ->getRepository(Games::class)
            ->findBy(['AwayTeam' => $id]);
        $stat = new Stat();
        $resultsArrayHome = array();
        $resultsArrayAway = array();
        $homeTeamsNames = array();
        $awayTeamsNames = array();
        $homeTeamsLogos = array();
        $awayTeamsLogos = array();
        for ($j = 0; $j < count($HomeTeam); $j++)
            array_push($resultsArrayHome, $HomeTeam[$j]->getFinal());
        $HomeWinsCoeff = $stat->GetHomeWinCoeff($resultsArrayHome);
        for ($k = 0; $k < count($AwayTeam); $k++)
            array_push($resultsArrayAway, $AwayTeam[$k]->getFinal());
        $AwayWinsCoeff = $stat->GetAwayWinCoeff($resultsArrayAway);
        for ($i = 0; $i < count($HomeTeam); $i++) {
            $final = $HomeTeam[$i]->getFinal();
            $away = $HomeTeam[$i]->getAwayTeam();
            $tablePosition = $stat->GetPosition($away);
            $result = $stat->GetResult($final);
            $resultOfMatch = $result * $HomeWinsCoeff / $tablePosition;
            $HomeMatchesResults += $resultOfMatch;
        }
        $HomeMatchesResults /= count($HomeTeam);
        for ($l = 0; $l < count($AwayTeam); $l++) {
            $final = $AwayTeam[$l]->getFinal();
            $home = $AwayTeam[$l]->getHomeTeam();
            $tablePosition = $stat->GetPosition($home);
            $result = $stat->GetResult($final);
            $resultOfMatch = $result * $AwayWinsCoeff / $tablePosition;
            $AwayMatchesResults += $resultOfMatch;
        }
        $AwayMatchesResults /= count($AwayTeam);
        $ScheduleHome = $this->getDoctrine()
            ->getRepository(Schedule::class)
            ->findBy(['HomeTeam' => $id]);
        $Selected = $this->getDoctrine()
            ->getRepository(Team::class)
            ->findBy(['number' => $id])[0];
        $Teams = $this->getDoctrine()
            ->getRepository(Team::class)
            ->findAll();
        $TeamsArray = array();
        for ($p = 0; $p < count($Teams); $p++){
            $arrayTeams = array();
            array_push($arrayTeams, $Teams[$p]->getId(), $Teams[$p]->getName(), $Teams[$p]->getLogo(), $Teams[$p]->getNumber());
            array_push($TeamsArray, $arrayTeams);
        }
        $First = $this->getDoctrine()
            ->getRepository(Team::class)
            ->find((int)$request->query->get('FirstTeamID'));
        $Second = $this->getDoctrine()
            ->getRepository(Team::class)
            ->find((int)$request->query->get('id'));
        if($FirstTeam = $request->query->get('FirstTeam') == null)
        {
            return $this->render('predict/index.html.twig', [
                'controller_name' => 'PredictController',
                'SelectedID' => $Selected->getId(),
                'SelectedName' => $Selected->getName(),
                'SelectedLogo' => $Selected->getLogo(),
                'Teams' => $TeamsArray,
                'HomePredict' => $HomeMatchesResults * $HomeWinsCoeff,
                'AwayPredict' => $AwayMatchesResults * $AwayWinsCoeff,
            ]);
        }
        else{
            if((int)$request->query->get('home') == 1)
            {
                return $this->render('predict/prediction.html.twig', [
                    'FirstTeamName' => $this->getDoctrine()
                        ->getRepository(Team::class)
                        ->find((int)$request->query->get('FirstTeamID'))->getName(),
                    'SecondTeamName' => $Selected->getName(),
                    'FirstTeamLogo' => $this->getDoctrine()
                        ->getRepository(Team::class)
                        ->find((int)$request->query->get('FirstTeamID'))->getLogo(),
                    'SecondTeamLogo' => $Selected->getLogo(),
                    'FirstTeamScore' => $request->query->get('FirstTeam'),
                    'SecondTeamScore' => $AwayMatchesResults * $AwayWinsCoeff,
                ]);
            }
            if((int)$request->query->get('home') == 0)
            {
                return $this->render('predict/prediction.html.twig', [
                    'FirstTeamName' => $this->getDoctrine()
                        ->getRepository(Team::class)
                        ->find((int)$request->query->get('FirstTeamID'))->getName(),
                    'SecondTeamName' => $Selected->getName(),
                    'FirstTeamLogo' => $this->getDoctrine()
                        ->getRepository(Team::class)
                        ->find((int)$request->query->get('FirstTeamID'))->getLogo(),
                    'SecondTeamLogo' => $Selected->getLogo(),
                    'FirstTeamScore' => $request->query->get('FirstTeam'),
                    'SecondTeamScore' => $HomeMatchesResults * $HomeWinsCoeff,
                ]);
            }
        }


//        $ScheduleAway = $this->getDoctrine()
//            ->getRepository(Schedule::class)
//            ->findBy(['AwayTeam' => $id]);
//            for ($m = 0; $m < count($ScheduleHome); $m++) {
//                $AwayTeams = $this->getDoctrine()
//                    ->getRepository(Team::class)
//                    ->findBy(['number' => $ScheduleHome[$m]->getAwayTeam()]);
//                array_push($awayTeamsNames, $AwayTeams[0]->getName());
//                array_push($awayTeamsLogos, $AwayTeams[0]->getLogo());
//            }
//            for ($n = 0; $n < count($ScheduleAway); $n++) {
//                $HomeTeams = $this->getDoctrine()
//                    ->getRepository(Team::class)
//                    ->findBy(['number' => $ScheduleAway[$n]->getHomeTeam()]);
//                array_push($homeTeamsNames, $HomeTeams[0]->getName());
//                array_push($homeTeamsLogos, $HomeTeams[0]->getLogo());
//            }

    }
}
class Stat{
    public function GetPosition($id)
    {
        switch ($id){
            case 885: return 9;
            case 854: return 19;
            case 345: return 18;
            case 841: return 15;
            case 875: return 10;
            case 865: return 3;
            case 874: return 14;
            case 876: return 11;
            case 880: return 4;
            case 869: return 1;
            case 479: return 2;
            case 882: return 5;
            case 866: return 13;
            case 878: return 20;
            case 57: return 7;
            case 879: return 12;
            case 884: return 8;
            case 867: return 17;
            case 881: return 16;
            case 808: return 6;
        }
    }
    public function GetResult($final)
    {
        $home = (int)$final{0};
        $away = (int)$final{2};
        if ($home > $away)
            return 2;
        elseif ($home < $away)
            return 0;
        else
            return 1;
    }
    public function GetHomeWinCoeff($results)
    {
        $homewin = 0;
        foreach ($results as $key => $resultValue)
        {
            $home = (int)$resultValue{0};
            $away = (int)$resultValue{2};
            if ($home > $away)
                $homewin++;
        }
        return $homewin/count($results);
    }
    public function GetAwayWinCoeff($results)
    {
        $awaywin = 0;
        foreach ($results as $key => $resultValue)
        {
            $home = (int)$resultValue{0};
            $away = (int)$resultValue{2};
            if ($home < $away)
                $awaywin++;
        }
        return $awaywin/count($results);
    }
}

