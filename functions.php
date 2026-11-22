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

/**
 * Check whether a post already contains an image in its content.
 *
 * @param int|WP_Post|null $post Post ID or object. Defaults to the current post.
 * @return bool
 */
function isko_post_content_has_image( $post = null ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	$content = $post->post_content;

	if ( preg_match( '/<img\b/i', $content ) || has_shortcode( $content, 'gallery' ) ) {
		return true;
	}

	$image_blocks = array( 'core/image', 'core/gallery', 'core/media-text', 'core/cover' );

	foreach ( $image_blocks as $block_name ) {
		if ( has_block( $block_name, $content ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Return the best available image HTML for a publication card.
 *
 * The featured image takes priority, followed by the first image in the post
 * content and then the first image attached to the post.
 *
 * @param int|WP_Post|null $post Post ID or object. Defaults to the current post.
 * @param string           $size WordPress image size.
 * @return string
 */
function isko_get_post_card_image( $post = null, $size = 'medium_large' ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	if ( has_post_thumbnail( $post ) ) {
		return get_the_post_thumbnail(
			$post,
			$size,
			array(
				'class'   => 'img-fluid',
				'loading' => 'lazy',
			)
		);
	}

	if ( preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/i', $post->post_content, $matches ) ) {
		$image_url = esc_url( html_entity_decode( $matches[1], ENT_QUOTES, get_bloginfo( 'charset' ) ) );

		if ( $image_url ) {
			return sprintf(
				'<img class="img-fluid" src="%1$s" alt="%2$s" loading="lazy">',
				$image_url,
				esc_attr( get_the_title( $post ) )
			);
		}
	}

	$attachments = get_attached_media( 'image', $post );
	if ( $attachments ) {
		$attachment = reset( $attachments );
		return wp_get_attachment_image(
			$attachment->ID,
			$size,
			false,
			array(
				'class'   => 'img-fluid',
				'loading' => 'lazy',
			)
		);
	}

	return '';
}
