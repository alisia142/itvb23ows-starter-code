<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Pieces\Ant;
use App\Board;

class AntTest extends TestCase 
{
    #[Test]
    public function testValidMoveNextToCurrentLocation() {
        $board = new Board([
            '1,-1' => [[0, 'Q']],
            '0,0' => [[0, 'A']],
            '1,0' => [[1, 'Q']],
        ]);
        $ant = new Ant($board);
        $from = '0,0';
        $to = '0,1';

        $valid = $ant->validMove($from, $to);
        $this->assertTrue($valid);
    }

    #[Test]
    public function testInvalidMoveCurrentToCurrent() {
        $board = new Board([
            '0,0' => [[0, 'A']],
        ]);
        $ant = new Ant($board);
        $from = '0,0';
        $to = '0,0';

        $valid = $ant->validMove($from, $to);
        $this->assertFalse($valid);
    }
}
