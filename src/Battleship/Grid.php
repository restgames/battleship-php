<?php

namespace Battleship;

use Battleship\Ship\Ship;
use Battleship\Ship\ShipFactory;

class Grid
{
    const NUMBER_OF_SHIPS = 5;
    const NUMBER_OF_ROWS = 8;

    const WATER = 0;
    const HIT = 1;
    const SUNK = 2;

    private $grid;
    private $ships;

    public function __construct()
    {
        $this->initShips();
        $this->initGrid();
    }

    private function initShips()
    {
        $this->ships = [];
    }

    private function initGrid()
    {
        $this->grid = [];
        for ($i = 0; $i < self::NUMBER_OF_ROWS; $i++) {
            for ($j = 0; $j < self::NUMBER_OF_ROWS; $j++) {
                $this->grid[$j][$i] = Grid::WATER;
            }
        }
    }

    /**
     * @param string $string
     * @return Grid
     * @throws \Exception
     */
    public static function fromString($string)
    {
        $letters = str_split($string, self::NUMBER_OF_ROWS);

        $grid = new self();
        foreach ($letters as $y => $letter) {
            $numbers = str_split($letter);
            foreach ($numbers as $x => $number) {
                $number = (int) $number;
                if ($number === 0) {
                    continue;
                }

                $ship = ShipFactory::build($number);
                $direction = (isset($numbers[$x + 1]) && $numbers[$x + 1] === $numbers[$x]) ? Position::HORIZONTAL() : Position::VERTICAL();

                try {
                    $grid = $grid->placeShip(
                        $ship,
                        new Hole(
                            Hole::numberToLetter($y + 1),
                            $x + 1
                        ),
                        $direction
                    );
                } catch(\Exception $e) {

                }
            }
        }

        if (!$grid->areAllShipsPlaced()) {
            throw new \Exception('Invalid format: All ships are not placed');
        }

        if ($string !== $grid->render()) {
            throw new \Exception('Invalid format');
        }

        return $grid;
    }

    /**
     * @param Ship $ship
     * @param Hole $hole
     * @param Position $position
     * @return $this
     * @throws ShipAlreadyPlacedException
     */
    public function placeShip(Ship $ship, Hole $hole, Position $position)
    {
        $grid = static::fromGrid($this);

        $shipId = get_class($ship);
        if (isset($grid->ships[$shipId])) {
            throw new ShipAlreadyPlacedException();
        }

        for ($i = 0; $i < $ship->size(); $i++) {
            $x = $grid->letterToNumber($hole->letter()) + ($position->equals(Position::VERTICAL()) ? $i : 0) - 1;
            $y = $hole->number() + ($position->equals(Position::HORIZONTAL()) ? $i : 0) - 1;

            if ($grid->grid[$x][$y] > 0) {
                throw new \InvalidArgumentException('Ship overlaps with another one, please choose another space.');
            }

            $grid->grid[$x][$y] = $ship->id();
            $grid->ships[$shipId][$x.'-'.$y] = 0;
        }

        return $grid;
    }

    /**
     * @return string
     */
    public function render()
    {
        $out = '';
        for ($letter = 0; $letter < count($this->grid); $letter++) {
            for ($number = 0; $number < count($this->grid[$letter]); $number++) {
                $out .= $this->grid[$letter][$number];
            }
        }

        return $out;
    }

    private static function fromGrid(Grid $grid)
    {
        $new = new self();
        $new->grid = $grid->grid;
        $new->ships = $grid->ships;

        return $new;
    }

    /**
     * @return bool
     */
    public function areAllShipsPlaced()
    {
        return count($this->ships) === self::NUMBER_OF_SHIPS;
    }

    public function areAllShipsSunk()
    {
        return false;
    }

    /**
     * @throws \Exception
     */
    public function shot(Hole $hole)
    {
        if (!$this->areAllShipsPlaced()) {
            throw new \Exception('All ships must be placed before shooting');
        }

        foreach($this->ships as $ship) {

        }
    }

    private function letterToNumber($letter)
    {
        return ord(strtoupper($letter)) - ord('A') + 1;
    }
}