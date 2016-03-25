<?php

namespace Battleship\Ship;

abstract class Ship
{
    public function size()
    {
        return static::SIZE;
    }

    public function id()
    {
        return static::ID;
    }
}
