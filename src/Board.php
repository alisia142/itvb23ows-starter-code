<?php

namespace App;

class Board
{
    public const array OFFSETS = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

    private array $tiles;

    public function __construct(array $tiles = [])
    {
        $this->tiles = $tiles;
    }

    // returnes tiles
    public function getTiles(): array
    {
        return $this->tiles;
    }

    // returnes if position is empty
    public function isPositionEmpty($pos): bool
    {
        return !isset($this->tiles[$pos]);
    }

    // set tile to [player, piece]
    public function setPosition($pos, $player, $piece): void
    {
        $this->tiles[$pos] = [[$player, $piece]];
    }

    // returnes all positions
    public function getAllPositions(): array
    {
        return array_keys($this->tiles);
    }

    /**
     * returnes all positions that are owned by player
     * array_filter -> filters all positions on tile owned by player, given position and player
    */
    public function getAllPositionsOwnedByPlayer($player): array
    {
        return array_filter($this->getAllPositions(), fn($pos) => $this->isTileOwnedByPlayer($pos, $player));
    }

    /**
     * returnes if tile is owned by player, given position and player
     * counts tiles of positions and checks if the first element is equal to player
     */
    public function isTileOwnedByPlayer($pos, $player): bool
    {
        return $this->tiles[$pos][count($this->tiles[$pos])-1][0] == $player;
    }

    /**
     * returnes tile that is on given position
     * counts tile on given position
     */
    public function getTileOnPosition($pos): array
    {
        return $this->tiles[$pos][count($this->tiles[$pos])-1];
    }

    // removes tile from tiles on given position
    public function removeTile($pos): array
    {
        return array_pop($this->tiles[$pos]);
    }

    /**
     * adds tile to tiles on given position and tile
     * checks if position is empty, if true then tiles[pos] is set to empty. 
     * then tile is pushed into tiles[pos]
     */
    public function addTile($pos, $tile): void
    {
        if ($this->isPositionEmpty($pos)) {
            $this->tiles[$pos] = [];
        }
        array_push($this->tiles[$pos], $tile);
    }

    // returns if a is neighbour of b
    private function isNeighbour($a, $b): bool
    {
        $a = explode(',', $a);
        $b = explode(',', $b);
        if (
            ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) ||
            ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1)
        ) {
            return true;
        }
        if ($a[0] + $a[1] == $b[0] + $b[1]) {
            return true;
        }
        return false;
    }

    // returns if a has neighbour
    public function hasNeighBour($a): bool
    {
        foreach (array_keys($this->tiles) as $b) {
            if ($this->isNeighbour($a, $b)) {
                return true;
            }
        }
        return false;
    }

    // returns if neighbours of a are the same color as player
    public function neighboursAreTheSameColor($player, $a): bool
    {
        foreach ($this->tiles as $b => $st) {
            if (!$st) {
                continue;
            }
            $c = $st[count($st) - 1][0];
            if ($c != $player && $this->isNeighbour($a, $b)) {
                return false;
            }
        }
        return true;
    }

    // returns length of tile
    public function len($tile): int
    {
        return $tile ? count($tile) : 0;
    }

    /**
     * returns if its possible to slide from "from" to "to"
     * does multiple checks:
     * - checks neighbours
     * - calculates common neighbours
    */
    public function slide($from, $to): bool
    {
        if (!$this->hasNeighBour($to) || !$this->isNeighbour($from, $to)) {
            return false;
        }

        $b = explode(',', $to);
        $common = array_filter(
            $this->getNeighbours($to, fn($neighbour) => !$this->isPositionEmpty($neighbour)),
            fn($position) => $this->isNeighbour($to, $position)
        );

        if (
            !isset($this->tiles[$common[0]]) &&
            !isset($this->tiles[$common[1]]) &&
            !isset($this->tiles[$from]) &&
            !isset($this->tiles[$to])
        ) {
            return false;
        }
        return min(
            $this->len($this->tiles[$common[0]] ?? []),
            $this->len($this->tiles[$common[1]] ?? [])
        ) <= max(
            $this->len($this->tiles[$from] ?? []),
            $this->len($this->tiles[$to] ?? [])
        );
    }

    // checks if move will split hive
    public function hiveSplit($from, $to): bool
    {
        $board = clone $this;
        $board->removeTile($from);

        if (!$board->hasNeighBour($to)) {
            return true;
        } else {
            $all = $this->board->getAllPositions();
            $queue = [array_shift($all)];
            while ($queue) {
                $next = explode(',', array_shift($queue));
                foreach (Board::OFFSETS as $pq) {
                    list($p, $q) = $pq;
                    $p += $next[0];
                    $q += $next[1];
                    if (in_array("$p,$q", $all)) {
                        $queue[] = "$p,$q";
                        $all = array_diff($all, ["$p,$q"]);
                    }
                }
            }
            if ($all) {
                return true;
            }
        }
        return false;
    }

    // returns all neighbours based on given position
    public function getNeighbours($pos, $filter = null): array
    {
        $neighbours = [];
        [$x, $y] = explode(',', $pos);
        foreach (self::OFFSETS as [$dx, $dy]) {
            $nx = $x + $dx;
            $ny = $y + $dy;
            $neighbour = "$nx,$ny";

            if (!isset($filter) || $filter($neighbour)) {
                $neighbours[] = $neighbour;
            }
        }
        return $neighbours;
    }

    // returns if player is surrounded based on position
    public function isPlayerSurrounded($currentPlayer, $position): bool
    {
        $neighbours = $this->getNeighbours($position);

        foreach ($neighbours as $neighbour) {
            if($this->isPositionEmpty($neighbour)) {
                return false;
            }
        }

        return true;
    }
}
