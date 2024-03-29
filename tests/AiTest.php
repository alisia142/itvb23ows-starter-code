<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Ai;
use App\Board;
use App\Hand;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class AiTest extends Testcase
{
    use MockeryPHPUnitIntegration;

    #[Before]
    public function startMockery(): void
    {
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        Mockery::getConfiguration()->allowMockingMethodsUnnecessarily(false);
    }

    #[After]
    public function purgeMockeryContainer(): void
    {
        Mockery::close();
    }
    
    #[Test]
    public function checkIfSuggestionHasCorrectParameters()
    {
        // arrange
        $responseMock = new Response(200, [], json_encode(["play", "B", "0,0"]));
        $guzzleMock = Mockery::mock(Client::class);
        $guzzleMock->allows('post')->andReturn($responseMock);
        $ai = new Ai($guzzleMock);
        $moveCounter = 1;
        $hands = [
            '0' => new Hand(),
            '1' => new Hand(),
        ];
        $board = new Board();

        // act
        $ai->createSuggestion($moveCounter, $hands, $board);

        // assert
        $guzzleMock->shouldHaveReceived()->post('', [
            'json' => [
                'move_number' => $moveCounter,
                'hand' => [
                    $hands[0]->getPieces(),
                    $hands[1]->getPieces(),
                ],
                'board' => $board->getTiles(),
            ],
        ]);
    }

    #[Test]
    public function testCheckIfCreateSuggestionIsEqualToExpectedSuggestion()
    {
        // arrange
        $guzzleMock = Mockery::mock(Client::class);
        $guzzleMock->allows('post')->andReturns(new Response(body: '["play", "B", "0,0"]'));
        $ai = new Ai($guzzleMock);
        $moveCounter = 1;
        $hands = [
            0 => new Hand(),
            1 => new Hand(),
        ];
        $board = new Board();

        // act
        $suggestion = $ai->createSuggestion($moveCounter, $hands, $board);

        // assert
        $this->assertSame(['play', 'B', '0,0'], $suggestion);
    }
}
