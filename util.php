<?php

$GLOBALS['OFFSETS'] = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

function isNeighbour($a, $b) {
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

function hasNeighBour($a, $board) {
    foreach (array_keys($board) as $b) {
        if (isNeighbour($a, $b)) {
            return true;
        }
    }
}

function neighboursAreSameColor($player, $a, $board) {
    foreach ($board as $b => $st) {
        if (!$st) {
            continue;
        }
        $c = $st[count($st) - 1][0];
        if ($c != $player && isNeighbour($a, $b)) {
            return false;
        }
    }
    return true;
}

function len($tile) {
    return $tile ? count($tile) : 0;
}

function slide($board, $from, $to) {
    if (!hasNeighBour($to, $board)) {
        return false;
    }
    if (!isNeighbour($from, $to)) {
        return false;
    }
    $b = explode(',', $to);
    $common = [];
    foreach ($GLOBALS['OFFSETS'] as $pq) {
        $p = $b[0] + $pq[0];
        $q = $b[1] + $pq[1];
        if (isNeighbour($from, $p.",".$q)) {
            $common[] = $p.",".$q;
        }
    }

    if (!$board[$common[0]] && !$board[$common[1]] && !$board[$from] && !$board[$to]) {
        return false;
    }
    return min(len($board[$common[0]]), len($board[$common[1]])) <= max(len($board[$from]), len($board[$to]));
}

function validGrasshopper($board, $from, $to) {
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
    if (!isset($board[$pos])) {
        return false;
    }
    
    // Set pos naar eerst mogelijke positie
    while (isset($board[$pos])) {
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
