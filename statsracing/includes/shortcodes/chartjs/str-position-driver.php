<?php

/**
 * Graphique du classment d'une carrière d'un piolote.
 */
function str_Dataf1_Chartjs_Position_Driver($year, $driver, $constructor){
    global $wpdb;
    
    $name = str_Dataf1_Get_Name($driver);
    $color = hexToRgb($constructor->color);

    $query = $wpdb->prepare("
        SELECT YEAR(races.date) AS year,
               standings.points,
               standings.position
        FROM (
            SELECT MAX(raceId) AS last_raceId, YEAR(date) AS year
            FROM {$wpdb->prefix}str_dataf1_races
            GROUP BY YEAR(date)
        ) AS last_races
        INNER JOIN {$wpdb->prefix}str_dataf1_races AS races ON last_races.last_raceId = races.raceId
        INNER JOIN {$wpdb->prefix}str_dataf1_driver_standings AS standings ON races.raceId = standings.raceId
        WHERE standings.driverId = %s
        ORDER BY YEAR(races.date) ASC
    ", $driver->driverId);
    $results = $wpdb->get_results($query);

    $years = array();
    $positions = array();
    foreach ($results as $result) {
        $years[] = $result->year;
        $positions[] = $result->position;
    }

    $content = "<canvas id=\"{$driver->driverId}\" width=\"10\" height=\"5\"></canvas><script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>";
    $content .= "<script> var labels = " . json_encode($years) . "; var data = " . json_encode($positions) . ";";
    $content .= "var ctx = document.getElementById('{$driver->driverId}').getContext('2d');";
    $content .= "var myChart = new Chart(ctx, { type: 'line', data: {";
    $content .= "labels: labels, datasets: [{ label: 'Classement annuel de {$name}', data: data, pointStyle: false, backgroundColor: 'rgba({$color[0]}, {$color[1]}, {$color[2]}, 0.3)',";
    $content .= "cubicInterpolationMode: 'monotone', borderColor: 'rgba({$color[0]}, {$color[1]}, {$color[2]}, 1)', borderWidth: 1 }] },";
    $content .= "options: { scales: { y: { min: 0.9, max: 23, reverse: true,";
    $content .= "ticks: { callback: function(value, index, values) { if (value === 0.9) return '1'; else return value; } } } }, responsive: true, } });";
    $content .= "</script>";
    return $content;
}


?>