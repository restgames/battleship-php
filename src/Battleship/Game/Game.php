<?php

namespace Battleship\Game;

class Game
{
    private $gameId;
    private $grid;

    public function __construct($gameId, $grid)
    {
        $this->gameId = $gameId;
        $this->grid = $grid;
    }

    /**
     * @return string
     */
    public function gameId()
    {
        return $this->gameId;
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        return $this->grid;
    }
}