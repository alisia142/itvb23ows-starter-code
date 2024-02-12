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

    public function getTiles(): array
    {
        return $this->tiles;
    }

    public function isPositionEmpty($pos): bool
    {
        return !isset($this->tiles[$pos]);
    }

    public function setPosition($pos, $player, $piece): void
    {
        $this->tiles[$pos] = [[$player, $piece]];
    }

    public function getAllPositions(): array
    {
        return array_keys($this->tiles);
    }

    public function getAllPositionsOwnedByPlayer($player): array
    {
        return array_filter($this->getAllPositions(), fn($pos) => $this->isTileOwnedByPlayer($pos, $player));
    }

    public function isTileOwnedByPlayer($pos, $player): bool
    {
        return $this->tiles[$pos][count($this->tiles[$pos])-1][0] == $player;
    }

    public function removeTile($pos): array
    {
        return array_pop($this->tiles[$pos]);
    }

    public function addTile($pos, $tile): void
    {
        if ($this->isPositionEmpty($pos)) {
            $this->tiles[$pos] = [];
        }
        array_push($this->tiles[$pos], $tile);
    }

    private function isNeighbour($a, $b): bool
    {
        $a = explode(',', $a);
        $b = explode(',', $b);
        if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) {
            return true;
        }
        if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1) {
            return true;
        }
        if ($a[0] + $a[1] == $b[0] + $b[1]) {
            return true;
        }
        return false;
    }

    public function hasNeighBour($a): bool
    {
        foreach (array_keys($this->tiles) as $b) {
            if (isNeighbour($a, $b)) {
                return true;
            }
        }
    }

    public function neighboursAreSameColor($player, $a): bool
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

    public function len($tile): int
    {
        return $tile ? count($tile) : 0;
    }

    public function slide($from, $to): bool
    {
        if (!$this->hasNeighBour($to, $board) || !$this>isNeighbour($from, $to)) {
            return false;
        }
        $b = explode(',', $to);
        $common = [];
        foreach (self::OFFSETS as $pq) {
            $p = $b[0] + $pq[0];
            $q = $b[1] + $pq[1];
            if ($this->isNeighbour($from, $p.",".$q)) {
                $common[] = $p.",".$q;
            }
        }

        if (!$this->tiles[$common[0]] &&
            !$this->tiles[$common[1]] &&
            !$this->tiles[$from] &&
            !$this->tiles[$to]
        ) {
            return false;
        }
        return min(
            len($this->tiles[$common[0]]),
            len($this->tiles[$common[1]])
        ) <= max(
            len($this->tiles[$from]),
            len($this->tiles[$to])
        );
    }
}
