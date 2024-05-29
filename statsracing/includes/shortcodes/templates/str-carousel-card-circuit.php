<?php

/**
 * Récupère la liste des circuits.
 */
function str_Get_List_Circuits(){
    global $wpdb;
    $circuits = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}str_dataf1_circuits ORDER BY surname ASC");
    return $circuits;
}

/**
 * Systèmes de cartes des circuits.
 */
function str_Get_Carousel_Circuits($circuits, $carousel){
    $chemin = "../../wp-content/plugins/statsracing/";
    $content = "<link rel=\"stylesheet\" href=\"{$chemin}assets/CSS/str_style_card_circuit.css\">";
    $content .= "<link rel=\"stylesheet\" href=\"{$chemin}assets/CSS/carousel.css\">";
    $content .= "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css\">";
    $content .= "<div class=\"str_circuits\">";
    $circuithowCarousel = ['Albert Park', 'Amériques', 'Bahreïn', 'Bakou', 'Barcelone', 'Bugatti', 'Frères Rodríguez', 'Gilles Villeneuve',
        'Hockenheimring', 'Hungaroring', 'Imola', 'Interlagos', 'Jeddah', 'Le Mans', 'Losail', 'Marina Bay', 'Monaco', 'Monza', 'Paul Ricard', 'Portimão'];

    if ($carousel == "true" && is_array($circuithowCarousel) && !empty($circuithowCarousel)) {
        foreach ($circuithowCarousel as $circuitSurname) {
            foreach ($circuits as $circuit) {
                if ($circuit->surname == $circuitSurname) {
                    $flag = str_Get_Flag_English($circuit->country);
                    $content .= "<div class=\"str_circuit_box\" id=\"{$circuit->circuitRef}\">";
                        if ($circuit->url != "NULL") {
                            $content .= "<a href=\"{$circuit->url}\" target=\"_blank\" class=\"str_circuit_wiki\">@WIKI</a>";
                        }
                        $content .= "<img src=\"{$chemin}assets/Images/Circuits/{$circuit->circuitRef}.png\" alt=\"Image circuit\">";
                        $content .= "<div class=\"str_circuit_content\">";
                            $content .= "<p class=\"str_circuit_length\">$circuit->length KM</p>";
                            $content .= "<p class=\"str_circuit_turns\">$circuit->turns Turns</p>";
                            $content .= "<img src=\"{$chemin}assets/Images/Drapeau/{$flag}.png\" alt=\"Drapeau\" class=\"str_circuit_flag\">";
                            $content .= "<p class=\"str_circuit_name\">$circuit->surname</p>";
                        $content .= "</div>";
                    $content .= "</div>";
                    break;
                }
            }
        }
    } else {
        foreach ($circuits as $circuit) {
            $flag = str_Get_Flag_English($circuit->country);
            $content .= "<div class=\"str_circuit_box\" id=\"{$circuit->circuitRef}\">";
                if ($circuit->url != "NULL") {
                    $content .= "<a href=\"{$circuit->url}\" target=\"_blank\" class=\"str_circuit_wiki\">@WIKI</a>";
                }
                $content .= "<img src=\"{$chemin}assets/Images/Circuits/{$circuit->circuitRef}.png\" alt=\"Image circuit\">";
                $content .= "<div class=\"str_circuit_content\">";
                    $content .= "<p class=\"str_circuit_length\">$circuit->length KM</p>";
                    $content .= "<p class=\"str_circuit_turns\">$circuit->turns Turns</p>";
                    $content .= "<img src=\"{$chemin}assets/Images/Drapeau/{$flag}.png\" alt=\"Drapeau\" class=\"str_circuit_flag\">";
                    $content .= "<p class=\"str_circuit_name\">$circuit->surname</p>";
                $content .= "</div>";
            $content .= "</div>";
        }
    }
    if($carousel == "true"){
        $content .= "<a href=\"https://statsracing.fr/circuits\">";
            $content .= "<div class=\"str_circuit_box\" id=\"str_circuit_seemore\">";
                $content .= "<i class=\"fa-solid fa-magnifying-glass\"></i>";
                $content .= "<p>VOIR PLUS</p>";
            $content .= "</div>";
        $content .= "</a>";
    }
    $content .= "</div>";
    if($carousel == "true"){
        $content .= "<script type=\"module\" src=\"../../wp-content/plugins/statsracing/assets/JS/main.js\"></script>";
    } else {
        $content .= "<style>.str_circuits {display: grid;grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));grid-gap: 10px;}</style>";
    }
    return $content;
}

?>