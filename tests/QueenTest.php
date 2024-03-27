<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Pieces\Queen;
use App\Board;

class QueenTest extends TestCase
{
    #[Test]
    public function testValidMoveOnXAxis()
    {
        $board = new Board([
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $queen = new Queen($board);
        $from = '0,0';
        $to = '0,1';

        $valid = $queen->validMove($from, $to);

        $this->assertTrue($valid);
    }

    #[Test]
    public function testInvalidMoveNotOnXAxis()
    {
        $board = new Board([
            '0,0' => [[0, 'Q']],
            '0,1' => [[1, '']],
        ]);
        $queen = new Queen($board);
        $from = '0,0';
        $to = '1,1';

        $valid = $queen->validMove($from, $to);

        $this->assertFalse($valid);
    }

    #[Test]
    public function testInvalidMoveToOccupiedTile()
    {
        $board = new Board([
            '0,0' => [[0, 'Q']],
            '0,1' => [[1, '']],
            '0,2' => [[1, 'B']],
        ]);
        $queen = new Queen($board);
        $from = '0,0';
        $to = '0,2';

        $valid = $queen->validMove($from, $to);

        $this->assertFalse($valid);
    }
}
