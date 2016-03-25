<?php

namespace Battleship;

use Battleship\Ship\Ship;
use Battleship\Ship\ShipFactory;

class Grid
{
    const NUMBER_OF_SHIPS = 5;

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
        foreach (static::letters() as $i => $letter) {
            foreach (static::numbers() as $j => $number) {
                $this->grid[$i][$j] = static::WATER;
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
        $letters = str_split($string, count(static::numbers()));

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
            throw new \InvalidArgumentException('Invalid format: All ships are not placed');
        }

        if ($string !== $grid->render()) {
            throw new \InvalidArgumentException('Invalid format');
        }

        return $grid;
    }

    /**
     * @param Ship $ship
     * @param Hole $hole
     * @param Position $position
     * @return Grid
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

            if (!isset($grid->grid[$x][$y])) {
                throw new \OutOfBoundsException('Ship does not fit into the grid with such a hole and position');
            }

            if ($grid->grid[$x][$y] > 0) {
                throw new \InvalidArgumentException('Ship overlaps with another one, please choose another space.');
            }

            $grid->grid[$x][$y] = $ship->id();
            $grid->ships[$shipId] = $ship;
        }

        return $grid;
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

    /**
     * @param Hole $hole
     * @return int
     * @throws AllShipsAreNotPlacedException
     */
    public function shot(Hole $hole)
    {
        if (!$this->areAllShipsPlaced()) {
            throw new AllShipsAreNotPlacedException('All ships must be placed before shooting');
        }

        $y = Hole::letterToNumber($hole->letter()) - 1;
        $x = $hole->number() - 1;
        $shipId = $this->grid[$y][$x];
        if ($shipId !== 0) {
            $this->grid[$y][$x] = -abs($this->grid[$y][$x]);

            if ($this->isShipSunk($this->ships[abs($shipId)])) {
                return self::SUNK;
            }

            return self::HIT;
        }

        return self::WATER;
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
}