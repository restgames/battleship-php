<?php

namespace Battleship;

class PositionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider samePositionsDataProvider
     */
    public function givenTwoPositionsWithSameValueWhenComparingEqualityThenMustBeTrue($aPosition, $otherPosition)
    {
        $this->assertTrue($aPosition->equals($otherPosition));
    }

    public function samePositionsDataProvider()
    {
        return [
            [Position::fromHorizontal(), Position::fromHorizontal()],
            [Position::fromVertical(), Position::fromVertical()]
        ];
    }
}
