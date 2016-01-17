<?php

namespace Battleship;

use Battleship\Ship\Ship;
use Battleship\Ship\ShipFactory;

class Grid
{
    const NUMBER_OF_SHIPS = 5;
    const NUMBER_OF_ROWS = 10;
    const NUMBER_OF_COLUMNS = 10;

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
            for ($j = 0; $j < self::NUMBER_OF_COLUMNS; $j++) {
                $this->grid[$j][$i] = static::WATER;
            }
        }
    }

    /**
     * @return array
     */
    public static function letters()
    {
        return range('A', 'J');
    }

    /**
     * @return array
     */
    public static function numbers()
    {
        return range(1, 10);
    }

    /**
     * @param string $string
     * @return Grid
     * @throws \Exception
     */
    public static function fromString($string)
    {
        $letters = str_split($string, static::NUMBER_OF_ROWS);

        $grid = new self();
        foreach ($letters as $y => $letter) {
            $numbers = str_split($letter);
            foreach ($numbers as $x => $number) {
                $number = (int) $number;
                if ($number === 0) {
                    continue;
                }

                $ship = ShipFactory::build($number);
                $direction = (isset($numbers[$x + 1]) && $numbers[$x + 1] === $numbers[$x]) ? Position::fromHorizontal() : Position::fromVertical();

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

        $shipId = $ship->id();
        if (isset($grid->ships[$shipId])) {
            throw new ShipAlreadyPlacedException();
        }

        for ($i = 0; $i < $ship->size(); $i++) {
            $x = Hole::letterToNumber($hole->letter()) + ($position->equals(Position::fromVertical()) ? $i : 0) - 1;
            $y = $hole->number() + ($position->equals(Position::fromHorizontal()) ? $i : 0) - 1;

            if ($grid->grid[$x][$y] > 0) {
                throw new \InvalidArgumentException('Ship overlaps with another one, please choose another space.');
            }

            $grid->grid[$x][$y] = $ship->id();
            $grid->ships[$shipId] = $ship;
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
        $allShipsAreSunk = true;
        foreach($this->ships as $ship) {
            $allShipsAreSunk = $allShipsAreSunk && $this->isShipSunk($ship);
        }

        return $allShipsAreSunk;
    }

    /**
     * @param Hole $hole
     * @return int
     * @throws \Exception
     */
    public function shot(Hole $hole)
    {
        if (!$this->areAllShipsPlaced()) {
            throw new \Exception('All ships must be placed before shooting');
        }

        $y = Hole::letterToNumber($hole->letter()) - 1;
        $x = $hole->number() - 1;
        $shipId = $this->grid[$y][$x];
        if ($shipId !== 0) {
            $this->grid[$y][$x] = -abs($this->grid[$y][$x]);

            if ($this->isShipSunk($this->ships[$shipId])) {
                return self::SUNK;
            }

            return self::HIT;
        }

        return self::WATER;
    }

    private function isShipSunk($ship)
    {
        $size = $ship->size();

        $count = 0;
        foreach($this->grid as $y => $letter) {
            foreach($letter as $x => $number) {
                if ($this->grid[$y][$x] === -$ship->id()) {
                    $count++;
                }
            }
        }

        return $count === $size;
    }
}