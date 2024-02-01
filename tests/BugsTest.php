<?php

use PHPUnit\Framework\TestCase;
include_once('util.php');

final class GrasshopperTest extends TestCase {
    public function testGrasshopperValid() {
        $board = [
            '0,0' => ['W', 'Q'],
            '1,0' => ['B', 'A'],
        ];
        $from = '0,0';
        $to = '1,1';

        $valid = validGrasshopper($board, $from, $to);
        $this->assertTrue($valid);
    }

    public function testGrasshopperInvalid() {
        $board = [
            '0,0' => ['W', 'Q'],
            '1,0' => ['B', 'A'],
        ];
        $from = '0,0';
        $to = '1,2';

        $valid = validGrasshopper($board, $from, $to);
        $this->assertFalse($valid);
    }
}

final class AntTest extends TestCase {
    public function testAntValid() {
        $board = [
            '0,0' => ['W', 'Q'],
            '1,0' => ['B', 'A'],
        ];
        $from = '0,0';
        $to = '1,1';

        $valid = validAnt($board, $from, $to);
        $this->assertTrue($valid);
    }

    public function testAntInvalid() {
        $board = [
            '0,0' => ['W', 'Q'],
            '1,0' => ['B', 'A'],
        ];
        $from = '0,0';
        $to = '0,0';

        $valid = validAnt($board, $from, $to);
        $this->assertFalse($valid);
    }
}

final class SpiderTest extends TestCase {
    // TODO: Implement test for spider
}

final class QueenTest extends TestCase {
    // TODO: Implement test for Queen
}

final class BeetleTest extends TestCase {
    // TODO: Implement test for beetle
}

?>
