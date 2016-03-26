<?php

namespace Battleship\Ship;

class ShipTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider shipsDataProvider
     */
    public function givenAShipWhenAskingForSizeThenSizeMustMatch($ship, $expectedSize)
    {
        $this->assertSame($expectedSize, $ship->size());
    }

    public function shipsDataProvider()
    {
        return [
            [new Submarine(), Submarine::SIZE],
            [new Destroyer(), Destroyer::SIZE],
            [new Carrier(), Carrier::SIZE],
            [new Battleship(), Battleship::SIZE],
            [new Cruiser(), Cruiser::SIZE],
        ];
    }
}
