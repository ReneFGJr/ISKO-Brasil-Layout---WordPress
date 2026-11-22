<?php
/**
 * ISKO theme setup and assets.
 *
 * @package ISKO
 */

require_once get_template_directory() . '/inc/class-isko-github-updater.php';

function isko_setup() {
	load_theme_textdomain( 'isko', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'isko' ),
		)
	);
}
add_action( 'after_setup_theme', 'isko_setup' );

function isko_enqueue_assets() {
	wp_enqueue_style(
		'isko-bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
		array(),
		'5.3.3'
	);
	wp_enqueue_style(
		'isko-theme',
		get_stylesheet_uri(),
		array( 'isko-bootstrap' ),
		wp_get_theme()->get( 'Version' )
	);
	wp_enqueue_style(
		'isko-custom',
		get_template_directory_uri() . '/assets/css/theme.css',
		array( 'isko-theme' ),
		wp_get_theme()->get( 'Version' )
	);
	wp_enqueue_script(
		'isko-bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
		array(),
		'5.3.3',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'isko_enqueue_assets' );

function isko_nav_menu_css_class( $classes, $item, $args, $depth ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		if ( 0 === $depth ) {
			$classes[] = 'nav-item';

			if ( in_array( 'menu-item-has-children', $classes, true ) ) {
				$classes[] = 'dropdown';
			}
		}
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'isko_nav_menu_css_class', 10, 4 );

function isko_nav_menu_submenu_css_class( $classes, $args, $depth ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$classes[] = 'dropdown-menu';
	}

	return $classes;
}
add_filter( 'nav_menu_submenu_css_class', 'isko_nav_menu_submenu_css_class', 10, 3 );

function isko_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
		if ( 0 === $depth ) {
			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' nav-link' : 'nav-link';

			if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
				$atts['class'] .= ' dropdown-toggle';
				$atts['data-bs-toggle'] = 'dropdown';
				$atts['aria-haspopup'] = 'true';
				$atts['aria-expanded'] = 'false';
			}
		} else {
			$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' dropdown-item' : 'dropdown-item';
		}
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'isko_nav_menu_link_attributes', 10, 4 );

function isko_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'isko_excerpt_length' );

function isko_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'isko_excerpt_more' );
