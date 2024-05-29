<?php

/**
 * Système du boutton "Voir plus".
 */
function str_Button_LearnMore($link, $color){
    $chemin = "../../wp-content/plugins/statsracing/";
    $code_color = str_Get_Color_Category($color);
    $content = "<link rel=\"stylesheet\" href=\"{$chemin}assets/CSS/button.css\">";
    $content .= "<style>button.learn-more:hover .circle {background-color: {$code_color};}</style>";
    $content .= "<div id=\"container\"><a href=\"{$link}\"><button class=\"learn-more\">";
        $content .= "<span class=\"circle\" aria-hidden=\"true\">";
            $content .= "<span class=\"icon arrow\"></span>";
        $content .= "</span>";
        $content .= "<span class= \"button-text\">Voir Plus</span>";
    $content .= "</button></a></div>";
    return $content;
}

/**
 * Retourne la couleur pour chaque categories de sports.
 */
function str_Get_Color_Category($code_color){
    switch($code_color){
        case "f1":
            return "#FF1801";
        case "f2":
            return "#1C78FB";
        case "f3":
            return "#676766";
        case "gpexp":
            return "#FF014E";
        case "f1_academy":
            return "#BE117E";
        case "default":
            return "#000000";
        default:
            return "#000000";
    }
}

/**
 * Système des boutons de Réseaux Sociaux.
 */
function str_Social_Media_Button_System(){
    $list = ['instagram', 'x-twitter','facebook','globe','wikipedia-w','youtube','tiktok','threads','twitch','linkedin'];
    $title = ['Instagram', 'X (Twitter)','Facebook','Site Web','Wikipedia','Youtube','Tiktok','Threads','Twitch','Linkedin'];
    $chemin = "../../wp-content/plugins/statsracing/";
    $content = "<link rel=\"stylesheet\" href=\"{$chemin}assets/CSS/social_media_button.css\">";
    $content .= "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css\">";
    $content .= "<div class=\"social-btns\">";
    $i = 0;
    foreach($list as $social){
        $content .= "<a href=\"#\" title=\"{$title[$i]}\" class=\"btn {$social}\">";
            if($social == 'globe'){
                $content .= "<i class=\"fa fa-solid fa-{$social}\"></i>";
            } else {
                $content .= "<i class=\"fa fa-brands fa-{$social}\"></i>";
            }
        $content .= "</a>";
        $i++;
    }
    $content .= "</div>";
    return $content;
}

/**
 * Récupère le code de l'image d'un drapeau du pays.
 */
function str_Get_Flag($country){
    global $wpdb;
    $flag = $wpdb->get_results("SELECT flag FROM {$wpdb->prefix}str_nationality_flag WHERE country = \"$country\"");
    return $flag[0]->flag;
}

/**
 * Récupère le code de l'image d'un drapeau du pays en anglais.
 */
function str_Get_Flag_English($country){
    global $wpdb;
    $flag = $wpdb->get_results("SELECT flag FROM {$wpdb->prefix}str_nationality_flag WHERE country_english = \"$country\"");
    return $flag[0]->flag;
}