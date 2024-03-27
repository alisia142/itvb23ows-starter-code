<?php

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Board;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class BoardTest extends TestCase
{
    public static function boardConfiguration()
    {
        return [
            "Empty board" => [
                'tiles' => [],
            ],
            "Board with two queens" => [
                'tiles' => [
                    '0,0' => [["Q", 0]],
                    '0,1' => [["Q", 1]],
                ],
            ],
            "Board with only one piece" => [
                'tiles' => [
                    '0,0' => [["B", 0]],
                ],
            ],
            "Board with pieces of the same color" => [
                'tiles' => [
                    '0,0' => [["B", 0]],
                    '0,1' => [["B", 1]],
                    '0,2' => [["B", 2]],
                ],
            ],
            "Board with multiple pieces" => [
                'tiles' => [
                    '0,0' => [["B", 0]],
                    '1,1' => [["Q", 1]],
                    '2,2' => [["A", 2]],
                ],
            ],
        ];
    }

    #[DataProvider('boardConfiguration')]
    public function testBoard($tiles)
    {
        $board = new Board($tiles);
        $this->assertEquals($tiles, $board->getTiles());
    }
}
