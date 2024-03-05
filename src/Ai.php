<?php

namespace App;

use GuzzleHttp\Client;

class Ai
{
    private Client $client;

    public function __construct__(Client $client)
    {
        $this->client = $client;
    }

    public function createSuggestion($moveNumber, $hands, $board): array
    {
        $response = $this->client->request('POST', '', [
            'json' => [
                'move_number' => $moveNumber,
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
