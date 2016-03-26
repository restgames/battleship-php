<?php

namespace Battleship;

class HoleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidPairsOfNumbersAndLettersDataProvider
     *
     * @param $letter
     * @param $number
     */
    public function givenALetterOrANumberOutOfBoundsWhenCreatingAHoleThenAnExceptionIsThrown($letter, $number)
    {
        new Hole($letter, $number);
    }

    public function invalidPairsOfNumbersAndLettersDataProvider()
    {
        return [
            ['A', null],
            ['A', 0],
            ['A', -1],
            ['A', 11],
            [null, 1],
            [0,    1],
            ['K', 1],
            ['Hi to everyone!', 1],
        ];
    }

    /**
     * @test
     * @dataProvider validPairsOfNumbersAndLettersDataProvider
     *
     * @param $letter
     * @param $number
     */
    public function givenALetterAndANumberInOfBoundsWhenCreatingAHoleThenHoleIsCreated($letter, $number)
    {
        $this->assertInstanceOf('\Battleship\Hole', new Hole($letter, $number));
    }

    public function validPairsOfNumbersAndLettersDataProvider()
    {
        foreach (range(1, 10) as $number) {
            foreach (range('A', 'J') as $letter) {
                yield [$letter, $number];
            }
        }
    }

    /**
     * @test
     * @dataProvider numberToLetterDataProvider
     *
     * @param $number
     * @param $expectedLetter
     */
    public function givenANumberWhenAskingForItsLetterThenLetterOfThisNumberOrderShouldBeReturned($number, $expectedLetter)
    {
        $this->assertSame($expectedLetter, Hole::numberToLetter($number));
    }

    /**
     * @test
     * @dataProvider numberToLetterDataProvider
     *
     * @param $expectedNumber
     * @param $letter
     */
    public function givenALetterWhenAskingForItsNumberThenNumberOfThisLetterOrderShouldBeReturned($expectedNumber, $letter)
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
            [8, 'H'],
        ];
    }
}
