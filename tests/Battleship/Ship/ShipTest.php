<?php

namespace Battleship\Ship;

class ShipTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider shipsDataProvider
     */
    public function whenBuildingNewShipOrientationAndSizeMustMatch($ship, $expectedSize)
    {
        $this->assertSame($expectedSize, $ship->size());
    }

    public function shipsDataProvider()
    {
        return [
            [new Submarine(Position::VERTICAL()), Submarine::SIZE],
            [new Submarine(Position::HORIZONTAL()), Submarine::SIZE],
            [new Destroyer(Position::VERTICAL()), Destroyer::SIZE],
            [new Destroyer(Position::HORIZONTAL()), Destroyer::SIZE],
            [new Carrier(Position::VERTICAL()), Carrier::SIZE],
            [new Carrier(Position::HORIZONTAL()), Carrier::SIZE],
            [new Battleship(Position::VERTICAL()), Battleship::SIZE],
            [new Battleship(Position::HORIZONTAL()), Battleship::SIZE],
            [new Cruiser(Position::VERTICAL()), Cruiser::SIZE],
            [new Cruiser(Position::HORIZONTAL()), Cruiser::SIZE]
        ];
    }
}
