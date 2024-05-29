<?php

require_once plugin_dir_path(__FILE__) . 'shortcodes/system/str-color.php';
require_once plugin_dir_path(__FILE__) . 'shortcodes/system/str-element.php';

require_once plugin_dir_path(__FILE__) . 'shortcodes/templates/str-carousel-card-driver.php';
require_once plugin_dir_path(__FILE__) . 'shortcodes/templates/str-page-driver.php';
require_once plugin_dir_path(__FILE__) . 'shortcodes/templates/str-carousel-card-team.php';
require_once plugin_dir_path(__FILE__) . 'shortcodes/templates/str-carousel-card-circuit.php';
require_once plugin_dir_path(__FILE__) . 'shortcodes/templates/str-sources.php';

require_once plugin_dir_path(__FILE__) . 'shortcodes/data/str-data-constructor.php';
require_once plugin_dir_path(__FILE__) . 'shortcodes/data/str-data-driver.php';
require_once plugin_dir_path(__FILE__) . 'shortcodes/data/str-data-year.php';

require_once plugin_dir_path(__FILE__) . 'shortcodes/chartjs/str-position-driver.php';


/* ========== Éléments ========== */

function str_Button_More($atts) {
    $atts = shortcode_atts(array('link' => "statsracing.fr/f1/drivers", 'color' => "f1"), $atts, 'str_button_more');
    $link = $atts['link'];
    $color = $atts['color'];
    return str_Button_LearnMore($link, $color);
}

/* ========== Templates ========== */

function str_Drivers($atts){
    $atts = shortcode_atts(array('year' => "2023", 'carousel' => "true"), $atts, 'str_drivers');
    $year = $atts['year'];
    $carousel = $atts['carousel'];
    return str_Get_Drivers_Card($year, $carousel);
}

function str_Page_Driver($atts){
    $atts = shortcode_atts(array('year' => 2023), $atts, 'str_page_driver');
    $year = $atts['year'];
    return str_Page_Driver_Info($year);
}

function str_Teams($atts){
    $atts = shortcode_atts(array('year' => 2023, 'carousel' => "true"), $atts, 'str_teams');
    $year = $atts['year'];
    $carousel = $atts['carousel'];
    return str_Get_Carousel_Teams($year, $carousel);
}

function str_Circuits($atts){
    $atts = shortcode_atts(array('carousel' => "true"), $atts, 'str_circuits');
    $carousel = $atts['carousel'];
    $circuits = str_Get_List_Circuits();
    return str_Get_Carousel_Circuits($circuits, $carousel);
}

function str_Get_Sources(){
    return str_Get_List_Sources();
}

?>