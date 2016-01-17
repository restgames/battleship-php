<?php

namespace Battleship\Client;

use Battleship\Game\Game;
use Battleship\Grid;
use Battleship\Hole;

class LocalPlayer implements Player
{
    private $currentLetterIndex;
    private $currentNumberIndex;
    private $grid;
    private $letters;
    private $numbers;

    public function __construct()
    {
        $this->letters = range('A', 'G');
        $this->numbers = range(1, 8);

        $this->currentLetterIndex = 0;
        $this->currentNumberIndex = 0;
    }

    public function startGame()
    {
        $this->grid =
            (new Grid())
            ->placeShip(new Submarine(), new Hole('A', 1), Position::HORIZONTAL())
            ->placeShip(new Battleship(), new Hole('B', 1), Position::HORIZONTAL())
            ->placeShip(new Carrier(), new Hole('C', 1), Position::HORIZONTAL())
            ->placeShip(new Destroyer(), new Hole('D', 1), Position::HORIZONTAL())
            ->placeShip(new Cruiser(), new Hole('E', 1), Position::HORIZONTAL());

        return new Game(
            1,
            $this->grid
        );

        $result->board = $this->grid->render();

        return $result;
    }

    public function fire($gameId)
    {
        return new Hole(
            $this->letters[$this->currentLetterIndex],
            $this->numbers[$this->currentNumberIndex]
        );
    }

    public function shotAt($gameId, $letter, $number)
    {
        return Grid::WATER;
    }

    public function finishGame($gameId)
    {

    }
}