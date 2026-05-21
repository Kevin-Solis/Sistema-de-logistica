<?php
declare(strict_types=1);

// Weighted graph: each department points to nearby departments and road distance in km.
// This does not use an API; the values are local approximate road distances for class use.
function guatemala_graph(): array
{
    return [
        'Alta Verapaz' => [
            'Baja Verapaz' => 105,
            'El Progreso' => 170,
            'Izabal' => 255,
            'Peten' => 315,
            'Quiche' => 205,
        ],
        'Baja Verapaz' => [
            'Alta Verapaz' => 105,
            'El Progreso' => 75,
            'Guatemala' => 150,
            'Quiche' => 135,
        ],
        'Chimaltenango' => [
            'Guatemala' => 55,
            'Sacatepequez' => 25,
            'Solola' => 90,
            'Quiche' => 125,
        ],
        'Chiquimula' => [
            'El Progreso' => 115,
            'Jalapa' => 95,
            'Jutiapa' => 140,
            'Zacapa' => 35,
        ],
        'El Progreso' => [
            'Alta Verapaz' => 170,
            'Baja Verapaz' => 75,
            'Chiquimula' => 115,
            'Guatemala' => 85,
            'Jalapa' => 80,
            'Zacapa' => 75,
        ],
        'Escuintla' => [
            'Guatemala' => 60,
            'Jutiapa' => 145,
            'Retalhuleu' => 190,
            'Sacatepequez' => 55,
            'Santa Rosa' => 80,
            'Suchitepequez' => 125,
        ],
        'Guatemala' => [
            'Baja Verapaz' => 150,
            'Chimaltenango' => 55,
            'El Progreso' => 85,
            'Escuintla' => 60,
            'Jalapa' => 100,
            'Sacatepequez' => 40,
            'Santa Rosa' => 75,
        ],
        'Huehuetenango' => [
            'Quetzaltenango' => 95,
            'Quiche' => 165,
            'San Marcos' => 140,
        ],
        'Izabal' => [
            'Alta Verapaz' => 255,
            'Peten' => 285,
            'Zacapa' => 70,
        ],
        'Jalapa' => [
            'Chiquimula' => 95,
            'El Progreso' => 80,
            'Guatemala' => 100,
            'Jutiapa' => 75,
            'Santa Rosa' => 85,
        ],
        'Jutiapa' => [
            'Chiquimula' => 140,
            'Escuintla' => 145,
            'Jalapa' => 75,
            'Santa Rosa' => 55,
        ],
        'Peten' => [
            'Alta Verapaz' => 315,
            'Izabal' => 285,
        ],
        'Quetzaltenango' => [
            'Huehuetenango' => 95,
            'Retalhuleu' => 50,
            'San Marcos' => 55,
            'Solola' => 95,
            'Totonicapan' => 35,
        ],
        'Quiche' => [
            'Alta Verapaz' => 205,
            'Baja Verapaz' => 135,
            'Chimaltenango' => 125,
            'Huehuetenango' => 165,
            'Solola' => 80,
            'Totonicapan' => 75,
        ],
        'Retalhuleu' => [
            'Quetzaltenango' => 50,
            'San Marcos' => 95,
            'Suchitepequez' => 45,
        ],
        'Sacatepequez' => [
            'Chimaltenango' => 25,
            'Escuintla' => 55,
            'Guatemala' => 40,
        ],
        'San Marcos' => [
            'Huehuetenango' => 140,
            'Quetzaltenango' => 55,
            'Retalhuleu' => 95,
        ],
        'Santa Rosa' => [
            'Escuintla' => 80,
            'Guatemala' => 75,
            'Jalapa' => 85,
            'Jutiapa' => 55,
        ],
        'Solola' => [
            'Chimaltenango' => 90,
            'Quetzaltenango' => 95,
            'Quiche' => 80,
            'Suchitepequez' => 75,
            'Totonicapan' => 70,
        ],
        'Suchitepequez' => [
            'Escuintla' => 125,
            'Retalhuleu' => 45,
            'Solola' => 75,
        ],
        'Totonicapan' => [
            'Quetzaltenango' => 35,
            'Quiche' => 75,
            'Solola' => 70,
        ],
        'Zacapa' => [
            'Chiquimula' => 35,
            'El Progreso' => 75,
            'Izabal' => 70,
        ],
    ];
}

// Returns the department names sorted for the select inputs.
function cities(): array
{
    $cities = array_keys(guatemala_graph());
    sort($cities);
    return $cities;
}

// Dijkstra algorithm for finding the shortest path between two departments.
function shortest_route(string $origin, string $destination): ?array
{
    $graph = guatemala_graph();

    if (!isset($graph[$origin], $graph[$destination])) {
        return null;
    }

    // Initial values: all cities start with infinite distance.
    $distances = [];
    $previous = [];
    $unvisited = [];

    foreach ($graph as $city => $_) {
        $distances[$city] = INF;
        $previous[$city] = null;
        $unvisited[$city] = true;
    }

    $distances[$origin] = 0;

    // Visit the closest unvisited city until the destination is reached.
    while ($unvisited !== []) {
        $current = null;

        foreach (array_keys($unvisited) as $city) {
            if ($current === null || $distances[$city] < $distances[$current]) {
                $current = $city;
            }
        }

        if ($current === null || $distances[$current] === INF) {
            break;
        }

        // Once the destination is the closest city, the shortest route is known.
        if ($current === $destination) {
            break;
        }

        unset($unvisited[$current]);

        foreach ($graph[$current] as $neighbor => $weight) {
            if (!isset($unvisited[$neighbor])) {
                continue;
            }

            $candidate = $distances[$current] + $weight;

            // Store the better route if this neighbor is cheaper through current.
            if ($candidate < $distances[$neighbor]) {
                $distances[$neighbor] = $candidate;
                $previous[$neighbor] = $current;
            }
        }
    }

    if ($distances[$destination] === INF) {
        return null;
    }

    // Rebuild the route by walking backward from destination to origin.
    $path = [];
    $step = $destination;

    while ($step !== null) {
        array_unshift($path, $step);
        $step = $previous[$step];
    }

    return [
        'distance' => (int) $distances[$destination],
        'path' => $path,
    ];
}

// Escapes text before printing it in HTML.
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
