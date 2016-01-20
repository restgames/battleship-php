Battleship PHP libraries
========================

[![Build Status](https://travis-ci.org/restgames/battleship-php.svg)](https://travis-ci.org/restgames/battleship-php)

A library that implements a Battleship Grid based in [Battleship Hasbro original game](http://www.hasbro.com/common/instruct/Battleship.PDF). You can place ships, shot at holes, and ask if the all ships are sunk.

Check an example.

    $battleshipGrid = Grid::fromString(
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
                $battleshipGrid->shot(new Hole($letter, $number))
            );
        }
    }

    $this->assertTrue($battleshipGrid->areAllShipsSunk());
