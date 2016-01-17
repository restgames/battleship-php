<?php

namespace Battleship\Ship;

use Battleship\Hole;

class PlacedShip
{
    const MISS = 0;
    const HIT = 1;
    const SUNK = 2;

    private $ship;

    /**
     * @var Hole[]
     */
    private $shipHoles;
    private $position;

    public function __construct(Ship $ship, Hole $hole, Position $position)
    {
        $this->ship = $ship;
        $this->hole = $hole;
        $this->position = $position;

        $this->shipHoles = [];
        for($i = 0; $i < $ship->size(); $i++) {
            $this->shipHoles[] = new Hole();
        }
    }

    public function shotAt(Hole $hole)
    {
        foreach($this->shipHoles as $shipHole) {
            if (!$shipHole->equals($hole)) {
                return self::MISS;
            }

            if () {

            }
        }
    }
}