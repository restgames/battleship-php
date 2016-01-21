<?php

namespace Battleship\Ship;

class ShipFactory
{
    public static function build($id)
    {
        switch ($id) {
            case Carrier::ID:
                return new Carrier();
                break;
            case Battleship::ID:
                return new Battleship();
                break;
            case Cruiser::ID:
                return new Cruiser();
                break;
            case Submarine::ID:
                return new Submarine();
                break;
            case Destroyer::ID:
                return new Destroyer();
                break;
        }

        throw new \Exception('Invalid Ship id');
    }
}