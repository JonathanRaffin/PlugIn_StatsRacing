<?php

/**
 * Systèmes de cartes des équipes. 
 */
function str_Get_Carousel_Teams($year, $carousel){
    $chemin = "../../wp-content/plugins/statsracing/";
    $constructors = str_Dataf1_Get_Constructors_Sort_By_Point($year);
    $nbConstructorsShow = ($carousel == "true") ? 10 : count($constructors);

    $content = "<link rel=\"stylesheet\" href=\"{$chemin}assets/CSS/str_style_card_team.css\">";
    $content .= "<link rel=\"stylesheet\" href=\"{$chemin}assets/CSS/carousel.css\">";
    $content .= "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css\">";
    $content .= "<div class=\"str_f1_teams\">";
    for($i = 0; $i < $nbConstructorsShow; $i++){
        $constructor = $constructors[$i];
        $flag = str_Get_Flag_English($constructor->nationality);
        $name_url = strtolower($constructor->constructorRef);
        $content .= "<div class=\"str_team_box\" id=\"{$constructor->constructorRef}\">";
            $content .= "<style>" . str_System_Color_Card_Team($constructor->constructorRef, $constructor->color) . "</style>";
            $content .= "<a href=\"#\" target=\"_blank\" class=\"str_team_src_img\">@src</a>";
            $content .= "<img class=\"str_team_img\" src=\"{$chemin}assets/Images/F1/Teams/{$constructor->constructorRef}.png\" alt=\"Image constructeur\">";
            $content .= "<div class=\"str_team_content\">";
                $content .= "<p class=\"str_team_name\">{$constructor->name}</p>";
                $content .= "<p class=\"str_team_chassis\">{$constructor->chassis}</p>";
                $content .= "<img src=\"{$chemin}assets/Images/Drapeau/{$flag}.png\" alt=\"Drapeau\" class=\"str_team_flag\">";
                $content .= "<p class=\"str_team_base\">$constructor->base</p>";
                $content .= "<p class=\"str_team_points\">{$constructor->points} PTS</p>";
            $content .= "</div>";
        $content .= "</div>";
    }
    if($carousel == "true" && $nbConstructorsShow > 10){
        $content .= "<a href=\"https://statsracing.fr/f1/teams/\">";
            $content .= "<div class=\"str_team_box\" id=\"str_team_seemore\">";
                $content .= "<i class=\"fa-solid fa-magnifying-glass\"></i>";
                $content .= "<p>VOIR PLUS</p>";
            $content .= "</div>";
        $content .= "</a>";
    }
    $content .= "</div>";
    if($carousel == "true"){
        $content .= "<script type=\"module\" src=\"../../wp-content/plugins/statsracing/assets/JS/main.js\"></script>";
    } else {
        $content .= "<style>.str_f1_teams {display: grid;grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));grid-gap: 10px;}</style>";
    }
    return $content;
}
