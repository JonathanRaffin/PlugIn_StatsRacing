<?php

/**
 * Systèmes de cartes des pilotes.
 */
function str_Get_Drivers_Card($year, $carousel){
    $chemin = "../../wp-content/plugins/statsracing/";
    $drivers = str_Dataf1_Get_Drivers_Sort_By_Point($year);

    // if($nbShow >= count($drivers)){
    //     $nbDriversShow = count($drivers);
    // } else {
        $nbDriversShow = ($carousel == "true") ? 10 : count($drivers);
    // }

    $content = "<link rel=\"stylesheet\" href=\"{$chemin}assets/CSS/str_style_card_driver.css\">";
    $content .= "<link rel=\"stylesheet\" href=\"{$chemin}assets/CSS/carousel.css\">";
    $content .= "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css\">";
    $content .= "<div class=\"str_f1_drivers\">";
    for($i = 0; $i < $nbDriversShow; $i++){
        $driver = $drivers[$i];
        $flag = str_Get_Flag_English($driver->nationality);
        $constructor = str_Dataf1_Get_Constructor($year, $driver->constructorId);

        /* $driverImagePath = "{$chemin}assets/Images/F1/Drivers2/{$driver->driverRef}.png";
        $defaultImagePath = "{$chemin}assets/Images/Driver.png";
        if(file_exists($driverImagePath)){
            $driverImage = $driverImagePath;
        } else {
            $driverImage = $defaultImagePath;
        }  */

        $content .= "<form name=\"driver-selection\" action=\"/driver/\" method=\"get\">";
            $surname = strtolower($driver->surname);
            $forename = strtolower($driver->forename);
            $content .= "<input type=\"hidden\" name=\"select-driver\" value={$forename}-{$surname}>";
            $content .= "<div class=\"str_driver_box\" id=\"". $constructor->constructorRef ."\"  onclick='window.location.href=\"statsracing.fr/driver/?select-driver={$forename}-{$surname}\"'>";
                $content .= "<style>" . str_System_Color_Card_Driver($constructor->constructorRef, $constructor->color) . "</style>";
                $content .= "<a href=\"statsracing.fr/sources\" target=\"_blank\" class=\"str_driver_src_img\">@src</a>";
                    $content .= "<img src=\"{$chemin}assets/Images/F1/Drivers2/{$driver->driverRef}.png\" alt=\"Image pilote\">";
                    // $content .= "<img src=\"{$driverImage}\" alt=\"Image pilote\">";
                $content .= "<div class=\"str_driver_content\">";
                    $content .= "<p class=\"str_driver_firstName\">$driver->forename</p>";
                    $content .= "<p class=\"str_driver_name\">$driver->surname</p>";
                    $content .= "<img src=\"{$chemin}assets/Images/Drapeau/{$flag}.png\" alt=\"Drapeau\" class=\"str_driver_flag\">";
                    $content .= "<p class=\"str_driver_teams\">$constructor->name</p>";
                    $content .= "<p class=\"str_driver_points\">{$driver->points} PTS</p>";
                $content .= "</div>";
            $content .= "</div>";
            $content .= "</input>";
        $content .= "</form>";
    }
    if($carousel == "true"){
        $content .= "<a href=\"https://statsracing.fr/f1/drivers/\">";
            $content .= "<div class=\"str_driver_box\" id=\"str_driver_seemore\">";
                $content .= "<i class=\"fa-solid fa-magnifying-glass\"></i>";
                $content .= "<p>VOIR PLUS</p>";
            $content .= "</div>";
        $content .= "</a>";
    }
    $content .= "</div>";
    $content .= "<script type=\"module\" src=\"../../wp-content/plugins/statsracing/assets/JS/teams.js\"></script>";
    if($carousel == "true"){
        $content .= "<script type=\"module\" src=\"../../wp-content/plugins/statsracing/assets/JS/main.js\"></script>";
    } else {
        $content .= "<style>.str_f1_drivers {display: grid;grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));grid-gap: 10px;}</style>";
    }
    return $content;
}