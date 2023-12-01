<?php
/*
Plugin Name: DSGVO Maps Disclaimer
Description: Ein Plugin zur Einbindung von Straßenkarten unter Berücksichtigung der Datenschutzgrundverordnung (DSGVO).
Version: 1.0
Author: Simon Zipperling
Plugin URI: https://simon.zipperling.net
Copyright: © 2023 Simon Zipperling
Author URI: https://simon.zipperling.net
*/



if (!function_exists('load_session')) {
    function load_session(){
        if(!session_id()){
            session_start();
        }
    }
}
add_action('init', 'load_session');

function create_dsgvo_maps( $atts ) {

    $atts = shortcode_atts( array(
        'url' => "#",
        'id' => "maps",
        'width' => "100%",
        'height' => "320px",
        'bg_color' => "#e6e6e6",
        'ft_color' => "#444",
        'bt_color' => "#222",
        'bt_ft_color' => "#fff",
        'dsgvo' => "#",
    ), $atts);

    $output = '<iframe src="'.$atts["url"].'" id="'.$atts["id"].'" frameborder="0" style="border:0; width: '.$atts["width"].'; height: '.$atts["height"].'; display: block;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
    
    $fail_to_load = '
        <div id="'.$atts['id'].'" style="background-color: '.$atts['bg_color'].'; width: '.$atts['width'].'; height: '.$atts['height'].'; display: block; border-radius: 5px; position: relative;">
            <div style="left: 50%; top: 50%; position: absolute; transform: translate(-50%, -50%);">
                <p style="color: '.$atts["ft_color"].'; font-weight: bold; text-align: center;">
                    Aus Datenschutzgründen, haben wir Google Maps eingeschränkt.</br><a href="'.$atts['dsgvo'].'" style="color: '.$atts['ft_color'].'; text-decoration: underline;">Mehr Erfahren.</a>
                    <form method="post" autocomplete="off">
                        <input type="hidden" name="id" value="'.$atts['id'].'">
                        <input type="submit" name="allow_maps" value="Erlauben" style="background-color: '.$atts['bt_color'].'; border: none; padding: 15px; border-radius: 5px; color: '.$atts['bt_ft_color'].'; text-align: center; width: 100%; cursor: pointer;">
                    </form>
                </p>
				<span style="font-size: 10px; color: '.$atts['ft_color'].'; display: block; text-align: center; padding: 10px;">© Simon Zipperling – <a target="_blank" href="https://simon.zipperling.net" style="color: #444;">https://simon.zipperling.net</a></span>
            </div>
        </div>';
    
    if(isset($_POST['allow_maps'])){
        $_SESSION['allow_maps'] = true;
        echo '<meta http-equiv="refresh" content="0; URL=?#'.$_POST['id'].'">';
    }

    if(isset($_SESSION['allow_maps'])){
        return $output;
    }elseif(!isset($_SESSION['allow_maps'])){
        return $fail_to_load;
    }
}

add_shortcode( 'maps', 'create_dsgvo_maps' );
?>