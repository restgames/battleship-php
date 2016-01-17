<?php

namespace Battleship;

class PositionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider samePositionsDataProvider
     */
    public function whenPositionsHaveSameValueShouldBeEqual($aPosition, $otherPosition)
    {
        $this->assertNotSame($aPosition, $otherPosition);
        $this->assertTrue($aPosition->equals($otherPosition));
    }

    public function samePositionsDataProvider()
    {
        return [
            [Position::HORIZONTAL(), Position::HORIZONTAL()],
            [Position::VERTICAL(), Position::VERTICAL()]
        ];
    }
}
