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
    public function givenAnEmptyGridWhenNoShipsArePlacedThenAllShipsAreNotPlaced()
    {
        $this->assertFalse($this->grid->areAllShipsPlaced());
    }

    /**
     * @test
     */
    public function givenAnEmptyGridWhenTwoShipsArePlacedThenAllShipsAreNotPlaced()
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
    public function givenAnEmptyGridWhenOneShipOfEveryTypeIsPlacedThenAllShipsArePlaced()
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
     * @dataProvider shipsOutOfBoundsDataProvider
     * @expectedException \OutOfBoundsException
     * @param $hole
     * @param $ship
     * @param $position
     */
    public function givenAnEmptyGridWhenPlacingAShipOutOfBoundsThenAnExceptionShouldBeThrown($hole, $ship, $position)
    {
        $this->grid->placeShip($ship, $hole, $position);
    }

    public function shipsOutOfBoundsDataProvider()
    {
        return [
            [new Hole('J', 10), new Submarine(), Position::fromHorizontal()],
            [new Hole('J', 10), new Submarine(), Position::fromVertical()],
            [new Hole('A', 10), new Destroyer(), Position::fromHorizontal()],
            [new Hole('J', 1), new Destroyer(), Position::fromVertical()],
        ];
    }

    /**
     * @test
     * @expectedException \Battleship\ShipAlreadyPlacedException
     */
    public function givenAnEmptyGridWhenPlacingSameShipTypeTwiceThenAnExceptionShouldBeThrown()
    {
        $this->grid
            ->placeShip(new Battleship(), new Hole('A', 1), Position::fromHorizontal())
            ->placeShip(new Battleship(), new Hole('B', 1), Position::fromHorizontal());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function givenAnEmptyGridWhenTwoShipsOfDifferentTypeAreOverlappedThenAnExceptionShouldBeThrown()
    {
        $this->grid
            ->placeShip(new Submarine(), new Hole('A', 1), Position::fromHorizontal())
            ->placeShip(new Battleship(), new Hole('A', 3), Position::fromHorizontal());
    }

    /**
     * @test
     */
    public function givenAnEmptyGridWhenPlacingAShipThenANewGridShouldBeReturnedAkaGridsAreImmutable()
    {
        $this->assertNotSame(
            $this->grid,
            $this->grid->placeShip(new Submarine(), new Hole('A', 1), Position::fromHorizontal())
        );
    }

    /**
     * @test
     */
    public function givenAnEmptyGridWhenRenderingGridThenA100LengthStringWith0sShouldBeReturned()
    {
        $this->assertSame(
            str_repeat('0', 100),
            $this->grid->render()
        );
    }

    /**
     * @test
     * @dataProvider gridsAndTheirRenderingDataProvider
     * @param string $expectedRender
     * @param Grid $grid
     */
    public function givenAnEmptyGridWhenPlacingAllShipsThenRenderMustMathString($expectedRender, $grid)
    {
        $this->assertSame($expectedRender, $grid->render());
    }

    public function gridsAndTheirRenderingDataProvider()
    {
        return [
            [
                '1111100000'.
                '2222000000'.
                '3330000000'.
                '4440000000'.
                '5500000000'.
                '0000000000'.
                '0000000000'.
                '0000000000'.
                '0000000000'.
                '0000000000',
                (new Grid)
                    ->placeShip(new Carrier(), new Hole('A', 1), Position::fromHorizontal())
                    ->placeShip(new Battleship(), new Hole('B', 1), Position::fromHorizontal())
                    ->placeShip(new Cruiser(), new Hole('C', 1), Position::fromHorizontal())
                    ->placeShip(new Submarine(), new Hole('D', 1), Position::fromHorizontal())
                    ->placeShip(new Destroyer(), new Hole('E', 1), Position::fromHorizontal())
            ],
            [
                '4000000002'.
                '4000000002'.
                '4000000002'.
                '0000000002'.
                '0000000000'.
                '0000000000'.
                '0000000000'.
                '3000000005'.
                '3000000005'.
                '3000011111',
                (new Grid)
                    ->placeShip(new Carrier(), new Hole('J', 6), Position::fromHorizontal())
                    ->placeShip(new Battleship(), new Hole('A', 10), Position::fromVertical())
                    ->placeShip(new Cruiser(), new Hole('H', 1), Position::fromVertical())
                    ->placeShip(new Submarine(), new Hole('A', 1), Position::fromVertical())
                    ->placeShip(new Destroyer(), new Hole('H', 10), Position::fromVertical())
            ],
        ];
    }

    /**
     * @test
     * @dataProvider validStringGridsDataProvider
     * @param $validGridString
     */
    public function givenAValidGridStringWhenBuildingAGridFromItThenGridShouldBeValid($validGridString)
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
     * @expectedException \Battleship\AllShipsAreNotPlacedException
     */
    public function givenAnEmptyGridWhenShootingAtAnyHoleThenAnExceptionShouldBeThrown()
    {
        $this->grid->shot(new Hole('A', 1));
    }

    /**
     * @test
     */
    public function givenASunkShipWhenShootingAgainOnItThenSunkShouldBeReturned()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function givenANonSunkShipWhenShootingAgainOnAHitThenHitShouldBeReturned()
    {
        $this->markTestIncomplete();
    }

    /**
     * @test
     */
    public function givenAValidGridStringWhenBuildingAndShootingAllTheHolesThenAllShipsMustBeSunk()
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
