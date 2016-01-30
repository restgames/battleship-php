# Battleship PHP libraries

[![Build Status](https://travis-ci.org/restgames/battleship-php.svg)](https://travis-ci.org/restgames/battleship-php)

A PHP helper library that implements a Battleship grid based in [Battleship Hasbro original board game](http://www.hasbro.com/common/instruct/Battleship.PDF) with basic rules such as placing ships, shooting, getting shot result and checking whether all ships are sunk or not.

Check the [referee](https://github.com/restgames/battleship-client) project that makes two players play against each other. You'll find there the detail about the API REST you must implement.

### What's REST Games?

Welcome to REST Games! Our goal is to provide you some coding challenges that go beyond the katas. You will implement a small JSON REST API that will play a well known game. The cool part comes when two mates develop the same JSON REST API and a _Referee_ can make them play one against the other. Cool, isn't it?

### Entities

- Grid: 10x10 grid of holes
- Hole: a letter and a number (A, 3)
- Ship: what you place and must sink!
  - 1 x Carrier (5 holes and id #1)
  - 1 x Battleship (4 holes and id #2)
  - 1 x Cruiser (3 holes and id #3)
  - 1 x Submarine (3 holes and id #4)
  - 1 x Destroyer (2 holes and id #5)
- Position: of a Ship
  - Horizontal
  - Vertical

### Tests

    Battleship\Grid
     [x] Given an empty grid when no ships are placed then all ships are not placed
     [x] Given an empty grid when two ships are placed then all ships are not placed
     [x] Given an empty grid when one ship of every type is placed then all ships are placed
     [x] Given an empty grid when placing a ship out of bounds then an exception should be thrown
     [x] Given an empty grid when placing same ship type twice then an exception should be thrown
     [x] Given an empty grid when two ships of different type are overlapped then an exception should be thrown
     [x] Given an empty grid when placing a ship then a new grid should be returned aka grids are immutable
     [x] Given an empty grid when rendering grid then a 100 length string with 0s should be returned
     [x] Given an empty grid when placing all ships then render must math string
     [x] Given a valid grid string when building a grid from it then grid should be valid
     [x] Given a non valid grid string when building a grid from it then an exception should be thrown
     [x] Given an empty grid when shooting at any hole then an exception should be thrown
     [x] Given a hit ship when shooting again on the hit hole then hit should be returned again
     [x] Given a sunk ship when shooting again on the ship then sunk should be returned again
     [x] Given a valid grid string when building and shooting all the holes then all ships must be sunk aka a complete game

    Battleship\Hole
     [x] Given a letter or a number out of bounds when creating a hole then an exception is thrown
     [x] Given a letter and a number in of bounds when creating a hole then hole is created
     [x] Given a number when asking for its letter then letter of this number order should be returned
     [x] Given a letter when asking for its number then number of this letter order should be returned

    Battleship\Position
     [x] Given two positions with same value when comparing equality then must be true

    Battleship\Ship\Ship
     [x] Given a ship when asking for size then size must match

### Example

In the following example, we'll build a grid placing ships and will shoot from left to right and up to bottom of the grid. We'll check that result of the shots (miss, hit and sunk) will be ok. If you wnat more examples, take a look to the `tests` folder, more specifically, the `GridTest.php` file.

    /*
    Let's build the following grid
    0300222200
    0300000000
    0310000000
    0010005000
    0010005000
    0010044400
    0010000000
    0000000000
    0000000000
    0000000000
    */

    $grid = (new Grid)
        ->placeShip(new Carrier(), new Hole('C', 3), Position::fromVertical())
        ->placeShip(new Battleship(), new Hole('A', 5), Position::fromHorizontal())
        ->placeShip(new Cruiser(), new Hole('A', 2), Position::fromVertical())
        ->placeShip(new Submarine(), new Hole('F', 6), Position::fromHorizontal())
        ->placeShip(new Destroyer(), new Hole('D', 7), Position::fromVertical())

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
                $battleshipGrid->shot(new Hole($letter, $number))
            );
        }
    }

    $this->assertTrue($battleshipGrid->areAllShipsSunk());

### Tips

- All the invalid conditions will throw an Exception. For example, shooting out of the bounds, placing a ship out of the bounds, building a Hole that is not valid, etc.

- You have a `ShipFactory` that might be useful.