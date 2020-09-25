<?php

namespace App\Queries;
use App\User;
use Faker\Factory;
use Illuminate\Support\Str;

class Teams
{
    private \Illuminate\Database\Eloquent\Collection $goalies;
    private \Illuminate\Database\Eloquent\Collection $nonGoalies;

    private int $totalTeams = 0;
    protected int $minimumPlayers = 18;
    protected int $maximumPlayers = 22;
    protected int $targetRankingForEachTeam = 0;

    public function index(): array
    {
        $this->goalies = User::userType('player')->canPlayGoalie()->get();

        $this->nonGoalies = User::userType('player')->cannotPlayGoalie()->get();

        $this->totalTeams = $this->calculateTotalNumberOfTeams($this->goalies->count(), $this->nonGoalies->count());

        $this->targetRankingForEachTeam = round(User::userType('player')->sum('ranking') / $this->totalTeams);

        return [
            'teams' => $this->assembleTeams()
        ];
    }

    private function calculateTotalNumberOfTeams($totalGoalies, $totalNonGoalies): int
    {
        $perTeam = floor($totalNonGoalies / $totalGoalies);

        if ($this->teamHasAdequatePlayers($perTeam)) {
            return $totalGoalies;
        }

        $divideBy = 2;

        while (!$this->teamHasAdequatePlayers(($totalGoalies + $totalNonGoalies) / $divideBy)) {
            if (($totalGoalies + $totalNonGoalies) / $divideBy <= 0) {
                return 0;
            } else {
                $divideBy *= 2;
            }
        }

        return $divideBy;
    }

    private function assembleTeams(): \Illuminate\Support\Collection
    {
        $teams = $this->generateTeamNames();

        $teams->transform(function ($team) {
            $team['roster'] = $this->buildRosterForTeam();

            return $team;
        });

        return $teams;
    }

    private function generateTeamNames(): \Illuminate\Support\Collection
    {
        $faker = Factory::create();

        $teams = [];

        for ($i = 0; $i < $this->totalTeams; $i++) {
            $teams[] = [
                'name' => Str::title(Str::plural("The {$faker->safeColorName} {$faker->lastName}")),
            ];
        }

        return collect($teams);
    }

    private function buildRosterForTeam(): array
    {
        $goalie = $this->goalies->shift();

        $roster = [
            'players' => [],
        ];

        $roster['players'][] = [
            'is_goalie' => true,
            'player' => $goalie,
        ];

        $totalRanking = $goalie->ranking;
        $totalPlayers = 1;

        while (!$this->teamHasAdequatePlayers($totalPlayers)) {
            $player = $this->nonGoalies->shift();

            if ($player) {
                $roster['players'][] = [
                    'is_goalie' => false,
                    'player' => $player,
                ];

                $totalPlayers++;
                $totalRanking += $player->ranking;
            } else {
                break;
            }
        }

        while ($this->teamHasAdequatePlayers($totalPlayers) && !$this->teamHasAcceptableRanking($totalRanking)) {
            $player = $this->nonGoalies->shift();

            if ($player) {
                $roster['players'][] = [
                    'is_goalie' => false,
                    'player' => $player,
                ];

                $totalPlayers++;
                $totalRanking += $player->ranking;
            } else {
                break;
            }
        }

        $roster['total_ranking'] = $totalRanking;
        $roster['average_ranking'] = round($totalRanking / count($roster['players']));

        return $roster;
    }

    private function teamHasAdequatePlayers($count): bool
    {
        return $count >= $this->minimumPlayers && $count <= $this->maximumPlayers;
    }

    private function teamHasAcceptableRanking($ranking): bool
    {
        return $ranking >= ($this->targetRankingForEachTeam - 2) && $ranking <= ($this->targetRankingForEachTeam + 2);
    }
}
