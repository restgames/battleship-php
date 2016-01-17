<?php

namespace Battleship\Ship;

class ShipFactory
{
    public static function build($id)
    {
        switch ($id) {
            case 1:
                return new Carrier();
                break;
            case 2:
                return new Battleship();
                break;
            case 3:
                return new Cruiser();
                break;
            case 4:
                return new Submarine();
                break;
            case 5:
                return new Destroyer();
                break;
        }

        throw new \Exception('Invalid Ship id');
    }
}