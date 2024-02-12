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

    public function isTileOwnedByPlayer(): bool
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

    public function validGrasshopper($from, $to) {
        if ($from === $to) {
            return false;
        }
        $fromExplode = explode(',', $from);
        $toExplode = explode(',', $to);

        // Kijk waar de sprinkhaan heen moet
        if ($fromExplode[1] == $toExplode[1]) {
            if ($fromExplode[0] > $toExplode[0]) {
                $offset = [-1,0]; // Sprinkhaan naar links
            } else {
                $offset = [1, 0]; // Sprinkhaan naar rechts
            }
        } elseif ($fromExplode[0] == $toExplode[0]) {
            if ($fromExplode[1] > $toExplode[1]) {
                $offset = [0, -1]; // Sprinkhaan naar boven
            } else {
                $offset = [0, 1]; // Sprinkhaan naar beneden
            }
        } elseif ($fromExplode[1] == $toExplode[1] - ($fromExplode[0] - $toExplode[0])) {
            if ($fromExplode[0] > $toExplode[0]) {
                $offset = [-1, 1]; // Sprinkhaan naar linksbeneden
            } else {
                $offset = [1, -1]; // Sprinkhaan naar rechtsboven
            }
        }

        // Positie
        $p = $fromExplode[0] + $offset[0];
        $q = $fromExplode[1] + $offset[1];

        $pos = $p.",".$q;
        $posExplode = [$p, $q];

        // Kijk of de positie buren heeft
        if (!isset($this->tiles[$pos])) {
            return false;
        }
        
        // Set pos naar eerst mogelijke positie
        while (isset($this->tiles[$pos])) {
            $p = $posExplode[0] + $offset[0];
            $q = $posExplode[1] + $offset[1];

            $pos = $p.",".$q;
            $posExplode = [$p, $q];
        }
        // Check of positie gelijk is aan de gewenste bestemming
        if ($pos == $to) {
            return true;
        }
        return false;
    }

    function validAnt($from, $to) {
        if ($from === $to) {
            return false;
        }
        // Ant kan onbeperkt aantal keer verplaatsen dus haal hem van het bord
        unset($this->tiles[$from]);

        // Kijk of de ant hetzelfde mag als de bijenkoningin
        if ($this->slide($from, $to)) {
            return true;
        }
        
        return false;
    }

    function validSpider($from, $to) {
        if ($from === $to) {
            return false;
        }
        // Spider kan 3 keer verplaatsen dus haal hem van het bord
        unset($this->tiles[$from]);

        $visited = [];
        $tiles = array($from);
        $tiles[] = null;

        // laatst bezochte tile en hoevaak er al is verplaatst
        $previousTile = null;
        $depth = 0;
        while(!empty($tiles) && $depth < 3) {
            // krijg de eerste tile
            $tile = array_shift($tiles);
            if ($tile == null) {
                $depth++;
                $tiles[] = null;
                if (reset($tiles) == null) {
                    break;
                } else {
                    continue;
                }
            }
            // is de tile al bezocht
            if (!in_array($tile, $visited)) {
                $visited[] = $tile;
            }

            $b = explode(',', $tile);

            foreach(self::OFFSETS as $pq) {
                $p = $b[0] + $pq[0];
                $q = $b[1] + $pq[1];

                $pos = $p.','.$q;
                // is tile al bezocht + pos is niet al geweest + pos is beschikbaar + heeft buren
                if (!in_array($pos, $visited) && $pos != $previousTile && !isset($this->tiles[$pos]) && $this->hasNeighBour($board, $pos)) {
                    if ($pos == $to && $depth == 2) {
                        return true;
                    }
                    $tiles[] = $pos;
                }
                $previousTile = $tile;
            }
            return false;
        }
    }
}