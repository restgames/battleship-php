<?php

namespace Battleship\Client;

class Referee
{
    public function __construct(Player $playerA, Player $playerB)
    {
        $this->playerA = $playerA;
        $this->playerB = $playerB;
    }

    public function play()
    {
        list($gameIdA, $gridA) = $this->playerA->startGame();
        list($gameIdB, $gridB) = $this->playerB->startGame();

        try {
            $refereeGridA = Grid::fromString($gridA);
        } catch(NonValidGridException $e) {
            return $this->playerB;
        }

        try {
            $refereeGridB = Grid::fromString($gridB);
        } catch(NonValidGridException $e) {
            return $this->playerA;
        }

        $turn = 1;
        $winner = null;
        while ($winner === null || $turn < 64 * 2) {
            $shot = $this->playerA->fire($gameIdA);
            $shotResult = $this->playerB->shotAt($gameIdB, $shot);
            $refereeShotResult = $refereeGridB->shotAt($shot);
            if ($refereeShotResult !== $shotResult) {
                //throw new \Exception('Shot result should be '.$refereeShotResult);
                $winner = $this->playerA;
                break;
            }

            $shot = $this->playerB->fire($gameIdB);
            $shotResult = $this->playerA->shotAt($gameIdA, $shot);
            $refereeShotResult = $refereeGridA->shotAt($shot);

            if ($refereeShotResult !== $shotResult) {
                //throw new \Exception('Shot result should be '.$refereeShotResult);
                $winner = $this->playerB;
                break;
            }

            $turn++;
        }

        return $winner;
    }
}