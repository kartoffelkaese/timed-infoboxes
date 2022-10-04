<?php
/*
Plugin Name: Timed Infoboxes
Plugin URI: https://github.com/kartoffelkaese/timed-infoboxes
Description: Plugin für Funktionen zur zeitlichen Anzeige von Infoboxen in Wordpress
Author: Martin Urban
Author URI: https://github.com/kartoffelkaese/timed-infoboxes
Version: 1.0

/**
 * Notwendiges CSS für die Boxen:
    :root {
        --purple: #a50775;
        --green: #3ead48;
        --grey: #8c8c8c;
        --red: #f00;
        --mtgreen: #3ead48;
        --bradius:3px;
    }
    .blockdiv {
        text-align:center;
        margin: 20px 0;
        font-weight: lighter;
    }
    .blockdiv > a, a:visited, a:hover {
        color: #f5f6f9;
    }
    .block {
        border-radius: var(--bradius);
        margin: 20px 20%;
        padding: 15px;
        text-align: center;
    }
    @media screen and (max-width: 767px) {
        .block {
        margin: 0px 0px 5px 0px;
        padding: 5px;
        }
    }
    
 */

/* Verbiete den direkten Zugriff auf die Plugin-Datei */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *  Infobild auf der Startseite, feste Breite 709px, 
 * 
 * [infobild link="link-zu-einer-seite" bild="volle url zum bild" ende"YYYY-MM-DD"]
 *  
 */ 

add_shortcode('infobild','infobild_handler');
function infobild_handler($atts = array()) {
	shortcode_atts(array(
        'link' => '',
		'bild' => '',
		'ende' => ''
	), $atts);
    $link = $atts['link'];
    $bild = $atts['bild'];
	$ende = $atts['ende'];
	$enddatum = new DateTime($ende);
 	$heute = new DateTime(date('Y-m-d'));

	if ($enddatum >= $heute) {
		$blocks = '<div class="blockdiv">
        <a href="'.$link.'"><img src="'.$bild.'" width="709px"></a>
        </div>';
		return $blocks;
	}
}

/**
 *  Infobox auf der Startseite.
 * 
 *  [infobox anfang="YYYY-MM-DD" ende="YYYY-MM-DD" farbe="farbe"]
 *  
 *  Farben sind definiert in den :root-variables 
 *  => menü -> design -> customizer -> zusätzliches css
 * 
 */

add_shortcode('infobox','infobox_handler');
function infobox_handler($atts = array(), $content = null, $tag) {
	shortcode_atts(array(
		'anfang' => '',
		'ende' => '',
		'farbe' => false
	), $atts);
	$anfang = $atts['anfang'];
	$ende = $atts['ende'];
	$anfangsdatum = new DateTime($anfang);
	$enddatum = new DateTime($ende);
 	$heute = new DateTime(date('Y-m-d'));

	if (empty($anfang)) {
    	if ($enddatum >= $heute) {
 		$blocks = '<div class="blockdiv">
        <div class="block" style="background-color:var(--' . $atts['farbe'] . ');">'
 		. $content . '
        </div></div>';	
		return $blocks;
		}
    } else {
        if($anfangsdatum <= $heute && $enddatum >= $heute)  {
			$blocks = '<div class="blockdiv">
        	<div class="block" style="background-color:var(--' . $atts['farbe'] . ');">'
 			. $content . '
        	</div></div>';
			return $blocks;
		}
	}
}
?>
