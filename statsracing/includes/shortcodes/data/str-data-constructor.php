<?php

/**
 * Récupères les informations du constructeur.
 */
function str_Dataf1_Get_Constructor($year, $constructorId){
    global $wpdb;

    $subquery_points = $wpdb->prepare("
        SELECT constructorId, points
        FROM {$wpdb->prefix}str_dataf1_constructor_standings AS cs
        JOIN {$wpdb->prefix}str_dataf1_races AS r
        ON cs.raceId = r.raceId
        WHERE cs.constructorId = %s
        AND YEAR(r.date) = %s
        ORDER BY r.date DESC
        LIMIT 1
    ", $constructorId, $year);

    $query = $wpdb->prepare("
        SELECT c.*, s.points
        FROM {$wpdb->prefix}str_dataf1_constructors AS c
        LEFT JOIN ($subquery_points) AS s ON c.constructorId = s.constructorId
        WHERE c.constructorId = %s
    ", $constructorId);

    $constructor_details = $wpdb->get_results($query);
    return $constructor_details[0];
}