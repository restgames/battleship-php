<?php

namespace Battleship;

class Hole
{
    /**
     * @var string
     */
    private $letter;

    /**
     * @var int
     */
    private $number;

    /**
     * @param string $letter
     * @param int $number
     */
    public function __construct($letter, $number)
    {
        $this->setLetter($letter);
        $this->setNumber($number);
    }

    private function setLetter($letter)
    {
        if (!is_string($letter) || strlen($letter) !== 1) {
            throw new \InvalidArgumentException('It must be a single letter');
        }

        $this->letter = $letter;
    }

    private function setNumber($number)
    {
        if (!is_int($number) || $number > 8 || $number < 1) {
            throw new \InvalidArgumentException('It must be a number between 1 and 8');
        }

        $this->number = $number;
    }

    /**
     * @return string
     */
    public function letter()
    {
        return $this->letter;
    }

    /**
     * @return int
     */
    public function number()
    {
        return $this->number;
    }

    public function equals(Hole $hole)
    {
        return
            $this->letter === $hole->letter
            && $this->number === $hole->number;
    }

    public static function letterToNumber($letter)
    {
        return ord(strtoupper($letter)) - ord('A') + 1;
    }

    public static function numberToLetter($number)
    {
        return chr(ord('A') + $number - 1);
    }
}