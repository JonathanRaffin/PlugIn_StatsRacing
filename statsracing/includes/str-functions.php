<?php

require_once plugin_dir_path(__FILE__) . 'str-shortcodes.php';

// Ajout d'un menu Admin du Plug-in.
add_action('admin_menu', 'str_Add_Menu_Admin');

function str_Add_Menu_Admin(){
    add_menu_page('StatsRacing', 'STATSRACING', 'manage_options', 'statsracing/includes/templates/str-page-admin.php');
}

// Ajouts des différents Shortcodes.
add_action('init', 'str_Shortcodes_Init');

function str_Shortcodes_Init(){
    /* ===== Éléments ===== */
    add_shortcode( 'str_button_more', 'str_Button_More' );

    /* ===== Templates ===== */
    add_shortcode( 'str_drivers', 'str_Drivers' );
    add_shortcode( 'str_page_driver', 'str_Page_Driver' );
    add_shortcode( 'str_teams', 'str_Teams' );
    add_shortcode( 'str_circuits', 'str_Circuits' );
    add_shortcode( 'str_get_sources', 'str_Get_Sources');

}