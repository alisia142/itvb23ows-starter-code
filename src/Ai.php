<?php

namespace App;

use GuzzleHttp\Client;

class Ai
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    // created suggestion based on the move number, given hands and given board
    public function createSuggestion($moveCounter, $hands, $board): array
    {
        $response = $this->client->post('', [
            'json' => [
                'move_number' => $moveCounter,
                'hand' => [
                    $hands[0]->getPieces(),
                    $hands[1]->getPieces(),
                ],
                'board' => $board->getTiles(),
            ],
        ]);
        return json_decode($response->getBody()->getContents());
    }
}
