<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Pieces\Beetle;
use App\Board;

class BeetleTest extends TestCase
{
    #[Test]
    public function testInvalidMoveToOccupiedTile()
    {
        $board = new Board([
            '0,0' => [[0, 'B']],
            '0,1' => [[1, 'Q']],
            '0,-1' => [[0, 'G']],
        ]);
        $beetle = new Beetle($board);
        $from = '0,0';
        $to = '0,1';

        $valid = $beetle->validMove($from, $to);

        $this->assertFalse($valid);
    }

    #[Test]
    public function testInvalidMoveToStartingTile()
    {
        $board = new Board([
            '0,0' => [[0, 'B']],
            '0,1' => [[1, 'Q']],
        ]);
        $beetle = new Beetle($board);
        $from = '0,0';
        $to = '0,0';

        $valid = $beetle->validMove($from, $to);

        $this->assertFalse($valid);
    }

    #[Test]
    public function testMoveOnTopOfOtherInsect()
    {
        $board = new Board([
            '0,0' => [[0, 'B']],
            '0,1' => [[1, 'A']],
        ]);
        $beetle = new Beetle($board);
        $from = '0,0';
        $to = '0,1';

        $valid = $beetle->validMove($from, $to);

        $this->assertFalse($valid);
    }
}
