<?php
/**
 * Systèmes de la page des souces.
 * TODO : Bugs au niveau des liens !
 */
function str_Get_List_Sources(){
    global $wpdb;
    $sources = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}str_sources ORDER BY name ASC");
    $category = array();
    foreach($sources as $src){
        if(!in_array($src->category, $category)){
            array_push($category, $src->category);
        }
    }
    $content = "";
    foreach ($category as $cat){
        $content .= "<h2>{$cat}</h2><ul>";
        foreach ($sources as $src){
            if($src->category == $cat){
                $content .= "<li><strong>{$src->name}</strong>:";
                if($src->author != "NULL"){
                    $content .= " Image crée par {$src->author}.";
                } 
                if($src->modified == "TRUE"){
                    $content .= " Cette image a été modifié.";
                } 
                if($src->license != "NULL"){
                    $content .= " Elle est sous license: {$src->license}.";
                } 
                if($src->links != "NULL"){
                    $content .= " Vous pouvez la retrouver <U><a href=\"{$src->links}\" target=\"_blank\">ici</a></U>.";
                }
                $content .= "</li>";
            }
        }
        $content .= "</ul>";
    }
    return $content;
}