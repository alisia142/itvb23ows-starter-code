<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Pieces\Spider;
use App\Board;

final class SpiderTest extends TestCase
{
    #[Test]
    public function testMoveThreeSteps() {
        $board = new Board([
            '1,-1' => [[0, 'S']],
            '0,0' => [[0, 'Q']],
            '1,0' => [[1, 'Q']],
        ]);
        $spider = new Spider($board);
        $from = '1,-1';
        $to = '-1,1';

        $valid = $spider->validMove($from, $to);
        $this->assertFalse($valid);
    }

    #[Test]
    public function testMoveMoreThanThreeSteps() {
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
