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
            Position::HORIZONTAL()
        );

        $this->grid->placeShip(
            new Battleship(),
            new Hole('B', 2),
            Position::VERTICAL()
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
            ->placeShip(new Submarine(), new Hole('A', 1), Position::HORIZONTAL())
            ->placeShip(new Battleship(), new Hole('B', 1), Position::HORIZONTAL())
            ->placeShip(new Carrier(), new Hole('C', 1), Position::HORIZONTAL())
            ->placeShip(new Destroyer(), new Hole('D', 1), Position::HORIZONTAL())
            ->placeShip(new Cruiser(), new Hole('E', 1), Position::HORIZONTAL())
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
            ->placeShip(new Battleship(), new Hole('A', 1), Position::HORIZONTAL())
            ->placeShip(new Battleship(), new Hole('B', 1), Position::HORIZONTAL());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function whenTwoShipsOfDifferentTypeAreOverlappedAnExceptionShouldBeThrown()
    {
        $this->grid
            ->placeShip(new Submarine(), new Hole('A', 1), Position::HORIZONTAL())
            ->placeShip(new Battleship(), new Hole('A', 3), Position::HORIZONTAL());
    }

    /**
     * @test
     */
    public function whenPlacingShipsANewGridShouldBeReturnedAkaImmutabilityTest()
    {
        $this->assertNotSame(
            $this->grid,
            $this->grid->placeShip(new Submarine(), new Hole('A', 1), Position::HORIZONTAL())
        );
    }

    /**
     * @test
     */
    public function renderingGridWithAllShipsPlaces()
    {
        $this->assertSame(
            '44400000'.
            '22220000'.
            '11111000'.
            '55000000'.
            '33300000'.
            '00000000'.
            '00000000'.
            '00000000',
            $this->grid
                ->placeShip(new Submarine(), new Hole('A', 1), Position::HORIZONTAL())
                ->placeShip(new Battleship(), new Hole('B', 1), Position::HORIZONTAL())
                ->placeShip(new Carrier(), new Hole('C', 1), Position::HORIZONTAL())
                ->placeShip(new Destroyer(), new Hole('D', 1), Position::HORIZONTAL())
                ->placeShip(new Cruiser(), new Hole('E', 1), Position::HORIZONTAL())
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
                '44400000'.
                '22220000'.
                '11111000'.
                '55000000'.
                '33300000'.
                '00000000'.
                '00000000'.
                '00000000'
            ],
            [
                '03002222'.
                '03000000'.
                '03100000'.
                '00100050'.
                '00100050'.
                '00100444'.
                '00100000'.
                '00000000'
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
            '03002222'.
            '03000000'.
            '03100000'.
            '00100050'.
            '00100050'.
            '00100444'.
            '00100000'.
            '00000000'
        );

        $this->assertTrue($this->grid->areAllShipsPlaced());
        $this->assertFalse($this->grid->areAllShipsSunk());

        foreach(range('A', 'G') as $letter) {
            foreach(range(1, 8) as $number) {
                $this->grid->shot(new Hole($letter, $number));
            }
        }

        $this->assertTrue($this->grid->areAllShipsSunk());
    }
}
