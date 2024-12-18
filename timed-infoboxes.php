<?php
declare(strict_types=1);

/*
Plugin Name: Timed Infoboxes
Plugin URI: https://github.com/kartoffelkaese/timed-infoboxes
Description: Plugin für Funktionen zur zeitlichen Anzeige von Infoboxen in Wordpress
Author: Martin Urban
Author URI: https://github.com/kartoffelkaese/timed-infoboxes
Version: 2.0
Requires PHP: 8.3
*/

/* Verbiete den direkten Zugriff auf die Plugin-Datei */
if (!defined('ABSPATH')) exit;

/**
 * Infobox Shortcode Handler
 */
function infobox_handler(array $atts = [], ?string $content = null, string $tag = ''): ?string 
{
    $atts = [
        'anfang' => $atts['anfang'] ?? '',
        'ende' => $atts['ende'] ?? '',
        'farbe' => $atts['farbe'] ?? '',
        'sfarbe' => $atts['sfarbe'] ?? ''
    ];
    
    if (empty($atts['ende']) || empty($atts['farbe']) || empty($atts['sfarbe'])) {
        error_log("Timed Infoboxes: Erforderliche Parameter fehlen (ende, farbe oder sfarbe)");
        return null;
    }
    
    try {
        $heute = new DateTime('today');
        $enddatum = new DateTime($atts['ende']);
        
        $shouldDisplay = match(true) {
            empty($atts['anfang']) => $enddatum > $heute,
            default => (new DateTime($atts['anfang'])) <= $heute && $enddatum > $heute
        };
        
        return $shouldDisplay ? generate_infobox($atts['farbe'], $atts['sfarbe'], $content) : null;
        
    } catch (Exception $e) {
        error_log("Timed Infoboxes Error: " . $e->getMessage());
        return null;
    }
}

/**
 * Generiert das HTML für die Infobox
 */
function generate_infobox(string $farbe, string $sfarbe, ?string $content): string 
{
    return sprintf(
        '<div class="blockdiv"><div class="block" style="background-color:var(--%s);color:var(--%s);">%s</div></div>',
        esc_attr($farbe),
        esc_attr($sfarbe),
        wp_kses_post($content)
    );
}

add_shortcode('infobox', 'infobox_handler');
?>
