<?php

/**
 * Récupère les informations générales d'un pilote.
 */
function str_Dataf1_Get_Driver($year, $driverId){
    global $wpdb;

    $subquery_points = $wpdb->prepare("
        SELECT driverId, points
        FROM {$wpdb->prefix}str_dataf1_driver_standings AS ds
        JOIN {$wpdb->prefix}str_dataf1_races AS r
        ON ds.raceId = r.raceId
        WHERE ds.driverId = %s
        AND YEAR(r.date) = %s
        ORDER BY r.date DESC
        LIMIT 1
    ", $driverId, $year);

    $subquery_constructor = $wpdb->prepare("
        SELECT driverId, constructorId
        FROM {$wpdb->prefix}str_dataf1_driver_constructors
        WHERE year = %s AND driverId = %s
    ", $year, $driverId);

    $query = $wpdb->prepare("
        SELECT d.*, s.points, c.constructorId
        FROM {$wpdb->prefix}str_dataf1_drivers AS d
        LEFT JOIN ($subquery_points) AS s ON d.driverId = s.driverId
        LEFT JOIN ($subquery_constructor) AS c ON d.driverId = c.driverId
        WHERE d.driverId = %s
    ", $driverId);

    $driver_details = $wpdb->get_results($query);
    return $driver_details[0];
}

/**
 * Récupère les informations sur la carrière d'un pilote.
 */
function str_Dataf1_Get_Data_Career($driverId) {
    global $wpdb;
    $query = $wpdb->prepare("
        SELECT 
            COUNT(DISTINCT CASE WHEN position = 1 THEN raceId END) AS nbWins,
            COUNT(DISTINCT CASE WHEN position IN (1, 2, 3) THEN raceId END) AS nbPodiums,
            (
                SELECT COUNT(DISTINCT raceId)
                FROM {$wpdb->prefix}str_dataf1_results
                WHERE driverId = %s
                  AND positionText <> 'W'
            ) AS nbRaces,
            SUM(points) AS totalPoints,
            COUNT(DISTINCT CASE WHEN rank = 1 THEN raceId END) AS nbFastLap
        FROM 
            {$wpdb->prefix}str_dataf1_results
        WHERE 
            driverId = %s
    ", $driverId, $driverId);
    $results = $wpdb->get_row($query);

    if ($results) {
        $wins = isset($results->nbWins) ? intval($results->nbWins) : 0;
        $podiums = isset($results->nbPodiums) ? intval($results->nbPodiums) : 0;
        $races = isset($results->nbRaces) ? intval($results->nbRaces) : 0;
        $totalPoints = isset($results->totalPoints) ? intval($results->totalPoints) : 0;
        $fastLap = isset($results->nbFastLap) ? intval($results->nbFastLap) : 0;
        return array(
            'wins' => $wins,
            'podiums' => $podiums,
            'races' => $races,
            'totalPoints' => $totalPoints,
            'fastLap' => $fastLap
        );
    } else {
        return array(
            'wins' => 0,
            'podiums' => 0,
            'races' => 0,
            'totalPoints' => 0,
            'fastLap' => 0
        );
    }
}

/**
 * Récupère le nombre de fois où un pilote est Champion du Monde.
 */
function str_Dataf1_Get_Nb_Champion($driverId){
    global $wpdb;
    $query = "
        SELECT COUNT(r.raceId) AS champion_count
        FROM {$wpdb->prefix}str_dataf1_races r
        INNER JOIN (
            SELECT MAX(raceId) AS latest_race_id, year
            FROM {$wpdb->prefix}str_dataf1_races
            WHERE year BETWEEN 1950 AND 2023
            GROUP BY year
        ) AS latest_races
        ON r.raceId = latest_races.latest_race_id
        LEFT JOIN {$wpdb->prefix}str_dataf1_driver_standings s
        ON r.raceId = s.raceId
        WHERE s.driverId = $driverId AND s.position = 1
    ";
    $championCount = $wpdb->get_var($query);
    return $championCount;
}

/**
 * Récupère le nombre de Poles Positions (Premier au Qualifications).
 */
function str_Dataf1_Get_Nb_Pole_Position($driverId) {
    global $wpdb;
    $polePositionCount = $wpdb->get_var($wpdb->prepare("
        SELECT SUM(
            CASE
                WHEN ra.year = 2022 AND sr.grid = 1 THEN 1
                WHEN ra.year = 2022 AND sr.grid IS NULL AND r.grid = 1 THEN 1
                WHEN ra.year != 2022 AND r.grid = 1 THEN 1
                ELSE 0
            END
        ) AS polePositionCount
        FROM {$wpdb->prefix}str_dataf1_results AS r
        INNER JOIN {$wpdb->prefix}str_dataf1_races AS ra ON r.raceId = ra.raceId
        LEFT JOIN {$wpdb->prefix}str_dataf1_sprint_results AS sr ON r.raceId = sr.raceId AND sr.driverId = r.driverId
        WHERE r.driverId = %s
    ", $driverId));
    return $polePositionCount;
}

/**
 * Retourne le nom complet du pilote.
 */
function str_Dataf1_Get_Name($driver){
    $name = $driver->forename . " " . $driver->surname;
    return $name;
}

/**
 * Retourne l'age d'un pilote.
 */
function str_Dataf1_Get_Age($driver){
    $dobDate = new DateTime($driver->dob);
    $currentDate = new DateTime();
    $dobFormatted = $dobDate->format('Y-m-d');
    $currentFormatted = $currentDate->format('Y-m-d');
    $age = date_diff(date_create($dobFormatted), date_create($currentFormatted))->y;
    return $age;
}

/**
 * Met une date au format français.
 */
function format_date_fr($date) {
    $timestamp = strtotime($date);
    $mois = array(1=>'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
    $jour = date('j', $timestamp);
    $mois_num = date('n', $timestamp);
    $annee = date('Y', $timestamp);
    $date_formatee = $jour.' '.$mois[$mois_num].' '.$annee;
    return $date_formatee;
}

/**
 * Récupère l'id d'un pilote grâce à son nom et prénom.
 */
function str_Dataf1_Get_DriverId($driver){
    global $wpdb;
    $forename = strtolower($driver->forename);
    $surname = strtolower($driver->surname);
    $driverId = $wpdb->get_results($wpdb->prepare("SELECT driverId FROM {$wpdb->prefix}str_dataf1_drivers WHERE LOWER(forename) = %s AND LOWER(surname) = %s", $forename, $surname));
    if (!empty($driverId)) {
        return $driverId[0]->driverId;
    }
    return -1;
}

/**
 * Affiche le drapeau de la nationalité d'un pilote.
 */
function str_Dataf1_Get_Flag_Of_Driver($driver){
    $chemin = "../../wp-content/plugins/statsracing/";
    $flag = str_Get_Flag_English($driver->nationality);
    return "<img src=\"{$chemin}assets/Images/Drapeau/{$flag}.png\" alt=\"Drapeau\" class=\"str_driver_flag_ranking\">";
}

/**
 * Récupère le nom français de la nationalité d'un pilote.
 */
function str_Dataf1_Get_Country_FR_Of_Driver($driver){
    global $wpdb;
    $nationality = $wpdb->get_results("SELECT country FROM {$wpdb->prefix}str_nationality_flag WHERE country_english = \"$driver->nationality\"");
    return $nationality[0]->country;
}

/**
 * Récupère la différence de points entre le premier pilote et un pilote.
 */
function str_Dataf1_Get_Gap_Points($year, $driverId){
    $drivers = str_Dataf1_Get_Drivers_Of_Year($year);
    $firstDriver = str_Dataf1_Get_Driver($year, $drivers[0]);
    $driver = str_Dataf1_Get_Driver($year, $driverId);
    $value = $driver->points - $firstDriver->points;
    if($value != 0){
        return $value;
    } else {
        return "";
    }
}

/**
 * Récupère le contructeur actuel d'un pilote.
 */
function str_Dataf1_Get_ConstructorId_Of_Driver($year, $driverId){
    $driver = str_Dataf1_Get_Driver($year, $driverId);
    return $driver->constructorId;
}


/* ============ Sauvegarde ============ */
/* Ancienne requête */

/*
function str_Dataf1_Get_Data_Career($driverId){
    global $wpdb;
    $query = $wpdb->prepare("
        SELECT
            COUNT(DISTINCT CASE WHEN r.position = 1 THEN r.raceId END) AS nbWins,
            COUNT(DISTINCT CASE WHEN r.position IN (1, 2, 3) THEN r.raceId END) AS nbPodiums,
            (
                SELECT COUNT(DISTINCT raceId)
                FROM {$wpdb->prefix}str_dataf1_results
                WHERE driverId = %s
                    AND positionText <> 'W'
            ) AS nbRaces,
            SUM(r.points) AS totalPoints,
            COUNT(DISTINCT CASE WHEN r.rank = 1 THEN r.raceId END) AS nbFastLap,
            (
                SELECT COUNT(r.raceId) AS champion_count
                FROM {$wpdb->prefix}str_dataf1_races r
                INNER JOIN (
                    SELECT MAX(raceId) AS latest_race_id, year
                    FROM {$wpdb->prefix}str_dataf1_races
                    WHERE year BETWEEN 1950 AND 2023
                    GROUP BY year
                ) AS latest_races
                ON r.raceId = latest_races.latest_race_id
                LEFT JOIN {$wpdb->prefix}str_dataf1_driver_standings s
                ON r.raceId = s.raceId
                WHERE s.driverId = $driverId AND s.position = 1
            ) AS nbChampionships,
            SUM(
                CASE
                    WHEN ra.year >= 2022 AND sr.grid = 1 THEN 1
                    WHEN ra.year >= 2022 AND sr.grid IS NULL AND r.grid = 1 THEN 1
                    WHEN ra.year < 2022 AND r.grid = 1 THEN 1
                    ELSE 0
                END
            ) AS polePositionCount
        FROM {$wpdb->prefix}str_dataf1_results AS r
        LEFT JOIN {$wpdb->prefix}str_dataf1_races AS ra ON r.raceId = ra.raceId
        LEFT JOIN {$wpdb->prefix}str_dataf1_driver_standings AS s ON r.raceId = s.raceId AND s.driverId = r.driverId
        LEFT JOIN {$wpdb->prefix}str_dataf1_sprint_results AS sr ON r.raceId = sr.raceId AND sr.driverId = r.driverId
        WHERE r.driverId = %s
    ", $driverId, $driverId);
    $results = $wpdb->get_row($query);

    if ($results) {
        $wins = isset($results->nbWins) ? intval($results->nbWins) : 0;
        $podiums = isset($results->nbPodiums) ? intval($results->nbPodiums) : 0;
        $races = isset($results->nbRaces) ? intval($results->nbRaces) : 0;
        $totalPoints = isset($results->totalPoints) ? intval($results->totalPoints) : 0;
        $fastLap = isset($results->nbFastLap) ? intval($results->nbFastLap) : 0;
        $championships = isset($results->nbChampionships) ? intval($results->nbChampionships) : 0;
        $polePositionCount = isset($results->polePositionCount) ? intval($results->polePositionCount) : 0;
        return array(
            'wins' => $wins,
            'podiums' => $podiums,
            'races' => $races,
            'totalPoints' => $totalPoints,
            'fastLap' => $fastLap,
            'championships' => $championships,
            'polePositionCount' => $polePositionCount
        );
    } else {
        return array(
            'wins' => 0,
            'podiums' => 0,
            'races' => 0,
            'totalPoints' => 0,
            'fastLap' => 0,
            'championships' => 0,
            'polePositionCount' => 0
        );
    }
}
*/