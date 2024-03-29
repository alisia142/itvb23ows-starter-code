<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Pieces\Grasshopper;
use App\Board;

class GrasshopperTest extends TestCase
{
    #[Test]
    public function testValidMoveOnXAxis()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']],
            '0,1' => [[1, 'Q']],
        ]);
        $gh = new Grasshopper($board);
        $from = '0,0';
        $to = '0,2';

        // act
        $valid = $gh->validMove($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function testValidMoveOnYAxis()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']],
            '1,0' => [[1, 'Q']],
        ]);
        $gh = new Grasshopper($board);
        $from = '0,0';
        $to = '1,1';

        // act
        $valid = $gh->validMove($from, $to);

        // assert
        $this->assertTrue($valid);
    }

    #[Test]
    public function testInvalidMoveOnNonStraight()
    {
        // arrange
        $board = new Board([
            '0,0' => [[0, 'G']],
        ]);
        $gh = new Grasshopper($board);
        $from = '0,0';
        $to = '3,-2';

        // act
        $valid = $gh->validMove($from, $to);

        // assert
        $this->assertFalse($valid);
    }
}
