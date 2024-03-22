<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Ai;
use App\Board;
use App\Hand;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class AiTest extends Testcase
{
    use MockeryPHPUnitIntegration;

    /**
     @test
     */
    public function checkIfSuggestionHasCorrectParameters() {
        $responseMock = new Response(200, [], json_encode(["play", "B", "0,0"]));
        $guzzleSpy = Mockery::spy(Client::class);
        $guzzleSpy->allows('post')->andReturn($responseMock);
        
        $ai = new Ai($guzzleSpy);
        $moveNumber = 1;
        $hands = [
            '0' => new Hand(),
            '1' => new Hand(),
        ];
        $board = new Board();

        $ai->createSuggestion($moveNumber, $hands, $board);

        $guzzleSpy->shouldHaveReceived()->post('', [
            'json' => [
                'move_number' => $moveNumber,
                'hand' => [
                    $hands[0]->getPieces(),
                    $hands[1]->getPieces(),
                ],
                'board' => $board->getTiles(),
            ],
        ]);
    }
}
