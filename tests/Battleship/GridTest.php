<?php

namespace Battleship;

use Battleship\Ship\Battleship;
use Battleship\Ship\Carrier;
use Battleship\Ship\Cruiser;
use Battleship\Ship\Destroyer;
use Battleship\Ship\Submarine;

class GridTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Grid
     */
    private $grid;

    /**
     * @return Grid
     */
    protected function setUp()
    {
        $this->grid = new Grid();
    }

    /**
     * @test
     */
    public function whenEmptyGridAllShipsAreNotPlaced()
    {
        $this->assertFalse($this->grid->areAllShipsPlaced());
    }

    /**
     * @test
     */
    public function whenGridWithSomeShipsAreNotAllPlaced()
    {
        $this->grid->placeShip(
            new Submarine(),
            new Hole('A', 1),
            Position::fromHorizontal()
        );

        $this->grid->placeShip(
            new Battleship(),
            new Hole('B', 2),
            Position::fromVertical()
        );

        $this->assertFalse($this->grid->areAllShipsPlaced());
    }

    /**
     * @test
     */
    public function whenOneShipOfEveryTypeIsPlacedThenAllShipsArePlaced()
    {
        $this->assertTrue(
            $this->grid
            ->placeShip(new Submarine(), new Hole('A', 1), Position::fromHorizontal())
            ->placeShip(new Battleship(), new Hole('B', 1), Position::fromHorizontal())
            ->placeShip(new Carrier(), new Hole('C', 1), Position::fromHorizontal())
            ->placeShip(new Destroyer(), new Hole('D', 1), Position::fromHorizontal())
            ->placeShip(new Cruiser(), new Hole('E', 1), Position::fromHorizontal())
            ->areAllShipsPlaced()
        );
    }

    /**
     * @test
     * @expectedException \Battleship\ShipAlreadyPlacedException
     */
    public function whenSameShipTypeIsPlacedTwiceAnExceptionShouldBeThrown()
    {
        $this->grid
            ->placeShip(new Battleship(), new Hole('A', 1), Position::fromHorizontal())
            ->placeShip(new Battleship(), new Hole('B', 1), Position::fromHorizontal());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function whenTwoShipsOfDifferentTypeAreOverlappedAnExceptionShouldBeThrown()
    {
        $this->grid
            ->placeShip(new Submarine(), new Hole('A', 1), Position::fromHorizontal())
            ->placeShip(new Battleship(), new Hole('A', 3), Position::fromHorizontal());
    }

    /**
     * @test
     */
    public function whenPlacingShipsANewGridShouldBeReturnedAkaImmutabilityTest()
    {
        $this->assertNotSame(
            $this->grid,
            $this->grid->placeShip(new Submarine(), new Hole('A', 1), Position::fromHorizontal())
        );
    }

    /**
     * @test
     */
    public function renderingGridWithAllShipsPlaces()
    {
        $this->assertSame(
            '4440000000'.
            '2222000000'.
            '1111100000'.
            '5500000000'.
            '3330000000'.
            '0000000000'.
            '0000000000'.
            '0000000000'.
            '0000000000'.
            '0000000000',
            $this->grid
                ->placeShip(new Submarine(), new Hole('A', 1), Position::fromHorizontal())
                ->placeShip(new Battleship(), new Hole('B', 1), Position::fromHorizontal())
                ->placeShip(new Carrier(), new Hole('C', 1), Position::fromHorizontal())
                ->placeShip(new Destroyer(), new Hole('D', 1), Position::fromHorizontal())
                ->placeShip(new Cruiser(), new Hole('E', 1), Position::fromHorizontal())
                ->render()
        );
    }

    /**
     * @test
     * @dataProvider validStringGridsDataProvider
     * @param $validGridString
     */
    public function whenGridFromStringIsValidGridShouldBeValidToo($validGridString)
    {
        $this->assertSame($validGridString, Grid::fromString($validGridString)->render());
    }

    public function validStringGridsDataProvider()
    {
        return [
            [
                '4440000000'.
                '2222000000'.
                '1111100000'.
                '5500000000'.
                '3330000000'.
                '0000000000'.
                '0000000000'.
                '0000000000'.
                '0000000000'.
                '0000000000'
            ],
            [
                '0300222200'.
                '0300000000'.
                '0310000000'.
                '0010005000'.
                '0010005000'.
                '0010044400'.
                '0010000000'.
                '0000000000'.
                '0000000000'.
                '0000000000'
            ],
        ];
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function whenNotAllShipsArePlacedWeCannotShot()
    {
        $this->grid->shot(new Hole('A', 1));
    }

    /**
     * @test
     */
    public function completeGame()
    {
        $this->grid = Grid::fromString(
            '0300222200'.
            '0300000000'.
            '0310000000'.
            '0010005000'.
            '0010005000'.
            '0010044400'.
            '0010000000'.
            '0000000000'.
            '0000000000'.
            '0000000000'
        );

        $shotResults =
            '0100111200'.
            '0100000000'.
            '0210000000'.
            '0010001000'.
            '0010002000'.
            '0010011200'.
            '0020000000'.
            '0000000000'.
            '0000000000'.
            '0000000000';

        $this->assertTrue($this->grid->areAllShipsPlaced());
        $this->assertFalse($this->grid->areAllShipsSunk());

        foreach(Grid::letters() as $l => $letter) {
            foreach(Grid::numbers() as $n => $number) {
                $this->assertSame(
                    (int) $shotResults{$l * 10 + $n},
                    $this->grid->shot(new Hole($letter, $number))
                );
            }
        }

        $this->assertTrue($this->grid->areAllShipsSunk());
    }
}
