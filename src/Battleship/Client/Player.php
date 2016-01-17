<?php

namespace Battleship\Client;

use Battleship\Game\Game;
use Battleship\Hole;

interface Player
{
    /**
     * @return Game
     */
    public function startGame();

    /**
     * @param string $gameId
     * @return Hole
     */
    public function fire($gameId);

    /**
     * @param string $gameId
     * @param string $letter
     * @param int $number
     * @return mixed
     */
    public function shotAt($gameId, $letter, $number);

    /**
     * @param string $gameId
     */
    public function finishGame($gameId);
}