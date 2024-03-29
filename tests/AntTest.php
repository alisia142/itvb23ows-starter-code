<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Pieces\Ant;
use App\Board;

class AntTest extends TestCase
{
    #[Test]
    public function testValidMoveNextToCurrentLocation() {
        // arrange
        $board = new Board([
            '1,-1' => [[0, 'Q']],
            '0,0' => [[0, 'A']],
            '1,0' => [[1, 'Q']],
        ]);
        $ant = new Ant($board);
        $from = '0,0';
        $to = '0,1';

        // act
        $valid = $ant->validMove($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function testInvalidMoveCurrentToCurrent() {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'A']],
        ]);
        $ant = new Ant($board);
        $from = '0,0';
        $to = '0,0';

        // act
        $valid = $ant->validMove($from, $to);

        // assert
        $this->assertFalse($valid);
    }
}
