<?php

/**
 * Récupère la liste des pilotes d'une année.
 */
function str_Dataf1_Get_Drivers_Of_Year($year){
    global $wpdb;
    $lastRace = $wpdb->get_var($wpdb->prepare("SELECT raceId FROM {$wpdb->prefix}str_dataf1_races WHERE year = %s ORDER BY raceId DESC LIMIT 1", $year));
    $driversId = $wpdb->get_col($wpdb->prepare("SELECT driverId FROM {$wpdb->prefix}str_dataf1_driver_standings WHERE raceId = %s", $lastRace));
    $drivers = array();
    foreach ($driversId as $driverId) {
        array_push($drivers, str_Dataf1_Get_Driver($year, $driverId));
    }
    return $drivers;
}

/**
 * Récupère la liste des courses d'une année.
 */
function str_Dataf1_Get_Races_Of_Year($year){
    global $wpdb;
    $races = $wpdb->get_col($wpdb->prepare("SELECT * FROM {$wpdb->prefix}str_dataf1_races WHERE year = %s", $year));
    return $races;
}

/**
 * Récupère la listes des noms des courses d'une année.
 */
function str_Dataf1_Get_Races_Name_Of_Year($year){
    $races = str_Dataf1_Get_Races_Of_Year($year);
    $list = array_map(function($raceName){
        return substr($raceName, 0, -11);
    }, $races->name);
    return $list;
}

/**
 * Récupère la liste des pilotes d'une année triés par leur points au classement.
 */
function str_Dataf1_Get_Drivers_Sort_By_Point($year) {
    $drivers = str_Dataf1_Get_Drivers_Of_Year($year);
    function comparePoints($a, $b) {
        return $b->{'points'} - $a->{'points'};
    }
    usort($drivers, 'comparePoints');
    return $drivers;
}

/**
 * Récupère la liste des constructeurs d'une année.
 */
function str_Dataf1_Get_Constructors_Of_Year($year){
    global $wpdb;
    $lastRace = $wpdb->get_var($wpdb->prepare("SELECT raceId FROM {$wpdb->prefix}str_dataf1_races WHERE year = %s ORDER BY raceId DESC LIMIT 1", $year));
    $constructorsId = $wpdb->get_col($wpdb->prepare("SELECT constructorId FROM {$wpdb->prefix}str_dataf1_constructor_standings WHERE raceId = %s", $lastRace));
    $constructors = array();
    foreach ($constructorsId as $constructorId) {
        array_push($constructors, str_Dataf1_Get_Constructor($year, $constructorId));
    }
    return $constructors;
}

/**
 * Récupère la liste des constructeurs d'une année triés par leur points au classement.
 */
function str_Dataf1_Get_Constructors_Sort_By_Point($year) {
    $constructors = str_Dataf1_Get_Constructors_Of_Year($year);
    function comparePointsConstructors($a, $b) {
        return $b->{'points'} - $a->{'points'};
    }
    usort($constructors, 'comparePointsConstructors');
    return $constructors;
}