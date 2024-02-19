<?php

use PHPUnit\Framework\TestCase;
use App\Pieces\Grasshopper;
use App\Pieces\Ant;
use App\Pieces\Spider;

class GrasshopperTest extends TestCase 
{
    #[Test]
    public function testValidMoveOnXAxis() {
        $board = new Board([
            '0,0' => [[0, 'G']],
            '0,1' => [[1, 'Q']],
        ]);
        $gh = new Grasshopper($board);
        $from = '0,0';
        $to = '0,2';

        $valid = $gh->validMove($from, $to);
        $this->assertTrue($valid);
    }

    #[Test]
    public function testValidMoveOnYAxis() {
        $board = new Board([
            '0,0' => [[0, 'G']],
            '1,0' => [[1, 'Q']],
        ]);
        $gh = new Grasshopper($board);
        $from = '0,0';
        $to = '2,0';

        $valid = $gh->validMove($from, $to);
        $this->assertTrue($valid);
    }

    #[Test]
    public function testInvalidMoveOnNonStraight() {
        $board = new Board([
            '0,0' => [[0, 'G']],
        ]);
        $gh = new Grasshopper($board);
        $from = '0,0';
        $to = '3,-2';

        $valid = $gh->validMove($from, $to);
        $this->assertTrue($valid);
    }
}

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

final class SpiderTest extends TestCase
{
    #[Test]
    public function testMoveThreeStepsAway() {
        $board = new Board([
            '1,-1' => [[0, 'S']],
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $spider = new Spider($board);
        $from = '1,-1';
        $to = '-1,1';

        $valid = $spider->validMove($from, $to);
        $this->assertTrue($valid);
    }

    #[Test]
    public function testMoveMoreThanThreeStepsAway() {
        $board = new Board([
            '1,-1' => [[0, 'S']],
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $spider = new Spider($board);
        $from = '1,-1';
        $to = '0,1';

        $valid = $spider->validMove($from, $to);
        $this->assertFalse($valid);
    }
}

final class QueenTest extends TestCase {
    // TODO: Implement test for Queen
}

final class BeetleTest extends TestCase {
    // TODO: Implement test for beetle
}
