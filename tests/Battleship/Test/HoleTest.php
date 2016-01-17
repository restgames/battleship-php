<?php

namespace Battleship;

class HoleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider numberToLetterDataProvider
     * @param $number
     * @param $expectedLetter
     */
    public function numberToLetterConversion($number, $expectedLetter)
    {
        $this->assertSame($expectedLetter, Hole::numberToLetter($number));
    }

    /**
     * @test
     * @dataProvider numberToLetterDataProvider
     * @param $expectedNumber
     * @param $letter
     */
    public function letterToNumberConversion($expectedNumber, $letter)
    {
        $this->assertSame($expectedNumber, Hole::letterToNumber($letter));
    }

    public function numberToLetterDataProvider()
    {
        return [
            [1, 'A'],
            [2, 'B'],
            [3, 'C'],
            [4, 'D'],
            [5, 'E'],
            [6, 'F'],
            [7, 'G'],
            [8, 'H']
        ];
    }
}
