<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;

class PlayersIntegrityTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGoaliePlayersExist ()
    {
/*
		Check there are players that have can_play_goalie set as 1
*/
		$result = User::where('user_type', 'player')->where('can_play_goalie', 1)->count();
		$this->assertTrue($result > 1);

    }

    public function testTeamsAreEven()
    {
        $goalieCount = User::userType('player')->canPlayGoalie()->count();
        $nonGoaliesCount = User::userType('player')->cannotPlayGoalie()->count();
        $totalTeams = $this->calculateTotalNumberOfTeams($goalieCount, $nonGoaliesCount);

        $this->assertTrue($totalTeams % 2 == 0);
    }

    public function testAtLeastOneGoaliePlayerPerTeam ()
    {
        $goalieCount = User::userType('player')->canPlayGoalie()->count();
        $nonGoaliesCount = User::userType('player')->cannotPlayGoalie()->count();
        $totalTeams = $this->calculateTotalNumberOfTeams($goalieCount, $nonGoaliesCount);

        $this->assertTrue($goalieCount >= $totalTeams);
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

    private function teamHasAdequatePlayers($count): bool
    {
        return $count >= 18 && $count <= 22;
    }
}
