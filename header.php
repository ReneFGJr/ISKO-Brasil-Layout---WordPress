<?php
/**
 * The header for the ISKO theme.
 *
 * @package ISKO
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link visually-hidden-focusable" href="#conteudo"><?php esc_html_e( 'Pular para o conteúdo', 'isko' ); ?></a>
<header class="site-header">
	<nav class="navbar navbar-expand-lg" aria-label="<?php esc_attr_e( 'Navegação principal', 'isko' ); ?>">
		<div class="container">
			<div class="navbar-brand site-brand">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="brand-mark" aria-hidden="true">I</span>
					<span class="brand-name"><?php bloginfo( 'name' ); ?></span>
					</a>
				<?php endif; ?>
			</div>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#site-navigation" aria-controls="site-navigation" aria-expanded="false" aria-label="<?php esc_attr_e( 'Abrir menu', 'isko' ); ?>">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="site-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'navbar-nav ms-auto align-items-lg-center gap-lg-2',
						'fallback_cb'    => false,
						'depth'          => 2,
					)
				);
				?>
				<?php if ( ! has_nav_menu( 'primary' ) ) : ?>
					<ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
						<li class="nav-item"><a class="nav-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Início', 'isko' ); ?></a></li>
						<li class="nav-item"><a class="nav-link" href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ); ?>"><?php esc_html_e( 'Notícias', 'isko' ); ?></a></li>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	</nav>
</header>
