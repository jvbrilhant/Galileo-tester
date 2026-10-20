<?php
/**
 * Plugin Name:       Camp Hero
 * Description:       The camp hero section (fire + three tents) as an Elementor widget ("Camp Hero") and a [gc_camp_hero] shortcode.
 * Version:           1.4.1
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * License:           GPL-2.0-or-later
 * Text Domain:       gc-camp-hero
 *
 * @package GC_Camp_Hero
 */

defined( 'ABSPATH' ) || exit;

define( 'GC_CAMP_HERO_VERSION', '1.4.1' );
define( 'GC_CAMP_HERO_URL', plugin_dir_url( __FILE__ ) );
define( 'GC_CAMP_HERO_DIR', plugin_dir_path( __FILE__ ) );

require_once GC_CAMP_HERO_DIR . 'includes/render.php';

/**
 * Registers (does not enqueue) the hero CSS/JS. Elementor enqueues them only on pages
 * that use the widget; the shortcode enqueues them when it renders.
 */
function gc_camp_hero_register_assets() {
	wp_register_style( 'gc-camp-hero', GC_CAMP_HERO_URL . 'assets/css/camp-hero.css', array(), GC_CAMP_HERO_VERSION );
	wp_register_script( 'gc-camp-hero', GC_CAMP_HERO_URL . 'assets/js/camp-hero.js', array(), GC_CAMP_HERO_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
}
add_action( 'init', 'gc_camp_hero_register_assets' );

/**
 * Shortcode: [gc_camp_hero eyebrow="…" title="…" heading_tag="h1" header_height="80" header_overlay="no" height="100vh" height_mobile="100vh" fireflies="yes" fireflies_count="6" fireflies_speed="1"]
 *
 * @param array $atts Shortcode attributes.
 */
function gc_camp_hero_shortcode( $atts ) {
	$d    = gc_camp_hero_defaults();
	$atts = shortcode_atts(
		array(
			'eyebrow'       => $d['eyebrow'],
			'title'         => $d['title'],
			'heading_tag'   => 'h1',
			'header_height'  => 0,
			'header_overlay' => 'no',
			'height'          => '',
			'height_mobile'   => '',
			'fireflies'       => 'yes',
			'fireflies_count' => 6,
			'fireflies_speed' => 1,
		),
		$atts,
		'gc_camp_hero'
	);
	wp_enqueue_style( 'gc-camp-hero' );
	wp_enqueue_script( 'gc-camp-hero' );
	return gc_camp_hero_render( $atts );
}
add_shortcode( 'gc_camp_hero', 'gc_camp_hero_shortcode' );

/**
 * Elementor widget.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 */
function gc_camp_hero_register_widget( $widgets_manager ) {
	require_once GC_CAMP_HERO_DIR . 'includes/class-widget.php';
	$widgets_manager->register( new GC_Camp_Hero_Widget() );
}
add_action( 'elementor/widgets/register', 'gc_camp_hero_register_widget' );
