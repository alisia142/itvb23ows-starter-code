<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Pieces\Spider;
use App\Board;

class SpiderTest extends TestCase
{
    #[Test]
    public function testMoveMoreThanThreeSteps()
    {
        // arrange
        $board = new Board([
            '1,-1' => [[0, 'S']],
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $spider = new Spider($board);
        $from = '1,-1';
        $to = '0,1';

        // act
        $valid = $spider->validMove($from, $to);

        // assert
        $this->assertFalse($valid);
    }

    #[Test]
    public function testInvalidMoveCurrentToCurrent()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'S']],
        ]);
        $spider = new Spider($board);
        $from = '0,0';
        $to = '0,0';

        // act
        $valid = $spider->validMove($from, $to);

        // assert
        $this->assertFalse($valid);
    }
}
