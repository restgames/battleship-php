<?php

namespace Battleship\Client;

class RefereeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function givenPlayer1WithInvalidBoardWinnerIsPlayerB()
    {
        $invalidResult = new \stdClass();
        $invalidResult->gameId = '1';
        $invalidResult->board = strpad'1';


        $stub = $this->getMockBuilder('Battleship\Player')->getMock();
        $stub->method('startGame')->will($this->returnArgument());


        $playerB = new InvalidGridPlayer();

        $this->assertSame($winner, $playerB);
    }
}
