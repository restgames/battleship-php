<?php

namespace Battleship\Client;

use Battleship\Game\Game;
use Battleship\Grid;
use Battleship\Hole;
use GuzzleHttp\Client;

class RestApiPlayer implements Player
{
    private $client;
    private $endpoint;

    public function __construct($endpoint)
    {
        $this->client = new Client();
        $this->endpoint = $endpoint;
    }

    /**
     * @return Game
     */
    public function startGame()
    {
        $res = $this->client->request('POST', $this->endpoint.'/battleship/game');
        $response = json_decode($res->getBody());

        return new Game(
            $response->gameId,
            Grid::fromString($response->grid)
        );
    }

    /**
     * @param string $gameId
     * @return Hole
     */
    public function fire($gameId)
    {
        $res = $this->client->request('GET', $this->endpoint.'/battleship/game/'.$gameId.'/fire');
        $response = json_decode($res->getBody());

        return new Hole(
            $response->letter,
            $response->number
        );
    }

    /**
     * @param string $gameId
     * @param string $letter
     * @param int $number
     * @return mixed
     */
    public function shotAt($gameId, $letter, $number)
    {
        $this->client->request('POST', $this->endpoint.'/battleship/game/'.$gameId.'/shot-at');
    }

    /**
     * @param string $gameId
     */
    public function finishGame($gameId)
    {
        $this->client->request('DELETE', $this->endpoint.'/battleship/game/'.$gameId);
    }
}