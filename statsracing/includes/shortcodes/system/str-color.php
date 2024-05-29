<?php

/**
 * Système de couleur au survol des cartes de pilotes.
 */
function str_System_Color_Card_Driver($constructorId, $color){
    $content = "#{$constructorId}:hover {background-color: {$color};}";
    $content .= "#{$constructorId} .str_driver_teams {color: {$color};}";
    $content .= "#{$constructorId}:hover .str_driver_teams {color: white;}";
    return $content;
}

/**
 * Système de couleur au survol des cartes des équipes.
 */
function str_System_Color_Card_Team($constructorRef, $color){
    $chemin = "../../wp-content/plugins/statsracing/";
    $content = "#{$constructorRef}:hover {background-color: {$color};}";
    $content .= "#{$constructorRef} .str_team_base {color: {$color};}";
    $content .= "#{$constructorRef}:hover .str_team_base {color: white;}";

    $logoBlanc = ["alpine","alphatauri","haas","aston_martin","mclaren","williams"];
    if (in_array($constructorRef, $logoBlanc)) {
        $content .= "#{$constructorRef}:hover .str_team_img {content: url(\"{$chemin}assets/Images/F1/Teams/{$constructorRef}_blanc.png\")}";
    }
    return $content;
}

/**
 * Système de couleur pour la page d'un pilote.
 */
function str_System_Color_Page_Driver_Info($color){
    $content = ".profile-card{background-image: linear-gradient(0, #ffffff 15%, {$color} 85%);}";
    return $content;
}

/**
 * Transforme une couleur de format hexadecimal au format RGB.
 */
function hexToRgb($hex) {
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) != 6) {
        throw new InvalidArgumentException('La chaîne hexadécimale doit être de 6 caractères.');
    }
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return array($r, $g, $b);
}